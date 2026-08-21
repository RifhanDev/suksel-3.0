<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgotPassword;
use App\User;
use App\UserHistory;
use App\PasswordReminder;
use App\TwoFactorAuth;
use App\TwoFactorAuditLog;
use App\TwoFactorRecoveryCode;
use App\TwoFactorSetting;
use PragmaRX\Google2FA\Google2FA;
use Hash;
use Auth;
use Mail;
use Log;
use Carbon\Carbon;

class AuthController extends Controller
{

   public function login()
   {
      // If user is already logged in, redirect to appropriate dashboard
      if (auth()->check()) {
         $user = auth()->user();
         // dd($user);
         if ($user->hasRole('Vendor')) {
            return redirect('dashboard');
         } elseif ($user->can('Vendor:list')) {
            return redirect('vendors');
         } else {
            return redirect('agency/' . $user->organization_unit_id);
         }
      }

      // Show login page
      return view('auth.login');
   }

   public function doLogin(Request $request)
   {

      if (!is_null(session('attempt_again'))) {
         Log::Debug('!is_null attempt_again');
         $now = time();
         if ($now >= session('attempt_again')) {
            session()->forget('attempt');
            session()->forget('attempt_again');
         } else {
            $err_msg = trans('auth.alerts.too_many_attempts');
            return redirect('/auth/login')->withInput($request->except('password'))->with('error', $err_msg);
         }
      }

      if (is_null(session('attempt_again'))) {
         Log::Debug('is_null attempt_again');
         if (is_null(session('attempt'))) {
            session()->put('attempt', 0);
         }

         if (session('attempt') > 5) {
            $err_msg = trans('auth.alerts.too_many_attempts');
            return redirect('/auth/login')->withInput($request->except('password'))->with('error', $err_msg);
         } else {

            $user = User::where('username', $request->email)->where('password', md5($request->password))->orWhere('password', Hash::make($request->password))->first();

            if ($user) {
               $user->password = Hash::make($request->password);
               $user->save();
            }

            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {

               $user = auth()->user();

               if ($user->hasRole('Vendor') && !$user->confirmed) {
                  auth()->logout();
                  session()->flash('error', 'Sila sahkan alamat emel anda terlebih dahulu. Semak inbox emel anda untuk pautan pengesahan.');
                  return redirect('/auth/login');
               }

               session()->forget('attempt');

               // GATE A - already-enrolled users get challenged before the session
               // is established. Only the pending user id goes into the session;
               // the code itself is derived from the stored TOTP secret at verify time.
               if ($user->hasTwoFactorEnabled() && !$this->hasValidRememberDeviceCookie($request, $user)) {
                  auth()->logout();

                  session()->put('2fa_pending_user_id', $user->id);
                  session()->put('2fa_pending_remember', (bool) $request->input('remember'));
                  session()->save();

                  return redirect()->route('2fa.verify');
               }

               session()->save();

               UserHistory::log($user->id, 'sign-in');

               // Check if password needs to be changed (6 months expiration)
               if ($user->password_changed_at) {
                  $passwordChangedAt = Carbon::parse($user->password_changed_at);
                  $passwordAge = $passwordChangedAt->diffInMonths(Carbon::now());
                  if ($passwordAge >= 6) {
                     // Password expired, redirect to change password
                     session()->flash('warning', 'Kata laluan anda telah tamat tempoh (6 bulan). Sila tukar kata laluan anda.');
                     return redirect()->route('profile.force-password-change');
                  }
               } else {
                  // Password never changed, force change
                  session()->flash('warning', 'Sila tetapkan kata laluan anda.');
                  return redirect()->route('profile.force-password-change');
               }

               // GATE B - role requires 2FA but the user has not enrolled yet.
               // Same tier as the forced-password-change above: the user is logged in,
               // but is nagged (or blocked, past the grace period) until they enrol.
               if ($user->requiresTwoFactor() && !$user->hasTwoFactorEnabled()) {
                  $twoFactor = TwoFactorAuth::firstOrCreate(
                     ['user_id' => $user->id],
                     ['required_since' => now()]
                  );

                  // Backfill for rows created before the role requirement was switched on.
                  if (is_null($twoFactor->required_since)) {
                     $twoFactor->required_since = now();
                     $twoFactor->save();
                  }

                  $graceDays = TwoFactorSetting::current()->grace_period_days;
                  $deadline  = $twoFactor->required_since->copy()->addDays($graceDays);

                  if (now()->greaterThan($deadline)) {
                     session()->flash('warning', 'Tempoh tangguh pengesahan dua faktor telah tamat. Sila sediakan sekarang.');
                     return redirect()->route('2fa.setup');
                  }

                  // No flash message here — the reminder is rendered live on every page
                  // (see layouts.v3.master) by re-checking hasTwoFactorEnabled() directly,
                  // so it disappears the instant enrolment completes instead of lingering
                  // for a stale request or two after a session-flash would have expired.
               }

               return $this->redirectForUser($user);
            } else {
               $attempt = session('attempt');
               session()->put('attempt', $attempt += 1);

               if (session('attempt') > 5) {
                  $attempt_again = time() + (5 * 60);
                  session()->put('attempt_again', $attempt_again);
                  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
                  $err_msg = trans('auth.alerts.too_many_attempts');
                  return redirect('/auth/login')->withInput($request->except('password'))->with('error', $err_msg);
               } else {
                  $err_msg = trans('auth.alerts.wrong_credentials');
                  return redirect('/auth/login')->withInput($request->except('password'))->with('error', $err_msg);
               }
            }
         }
      }
   }

   /**
    * Attempt to do login via mobile
    * @return response
    */
   public function mobileLogin()
   {

      $inputBase = Input::all();
      $input = array(
         'email'    => $request->email, // May be the username too
         'username' => $request->email, // so we have to pass both
         'password' => $request->password,
      );

      if (Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
         $user = Auth::user();
         UserHistory::log($user->id, 'sign-in');
         try {
            $user->update(['auth_token' => UUID::uuid4()->toString()]);
         } catch (UnsatisfiedDependencyException $e) {
            // Some dependency was not met. Either the method cannot be called on a
            // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
            return App::abort('400', 'Caught exception: ' . $e->getMessage());
         }

         Auth::login($user);
         return $user;
      } else {

         $user = new User;
         if (Confide::isThrottled($input)) {
            $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
         } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
            $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
         } else {
            $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
         }
         return App::abort('400', $err_msg);
      }
   }

   /**
    * Attempt to confirm account with code
    *
    * @param    string  $code
    */
   public function confirm($code)
   {

      $user = User::where('confirmation_code', $code)->first();

      if ($user) {
         // Sahkan akaun pengguna apabila pautan pengesahan diklik
         $user->confirmed = 1;
         $user->confirmation_code = null;
         $user->save();
         $notice_msg = trans('auth.alerts.confirmation');
         return redirect('/')->with('notice', $notice_msg);
      } else {
         $error_msg = trans('auth.alerts.wrong_confirmation');
         return redirect('/')->with('error', $error_msg);
      }
   }

   /**
    * Displays the forgot password form
    *
    */
   public function forgotPassword()
   {
      return view('home.forgot_password');
   }

   /**
    * Attempt to send change password link to the given email
    *
    */
   public function doForgotPassword(Request $request)
   {

      $user = User::where('email', $request->email)->first();

      if ($user) {
         Mail::to($user)->send(new ForgotPassword($user));
         UserHistory::log($user->id, 'password-forget');
         $notice_msg = trans('auth.alerts.password_forgot');
         return redirect('/')->with('notice', $notice_msg);
      } else {
         $error_msg = trans('auth.alerts.wrong_password_forgot');
         return redirect('auth/forgot_password')->withInput()->with('error', $error_msg);
      }
   }

   /**
    * Shows the change password form with the given token
    *
    */
   public function resetPassword(Request $request, $token)
   {
      $email = PasswordReminder::where('token', $request->token)->first();
      if ($email)
         return view('home.reset_password')->with('token', $token);
      else {
         $error_msg = trans('auth.alerts.wrong_token');
         return redirect('/')->withInput()->with('error', $error_msg);
      }
   }

   /**
    * Attempt change password of the user
    *
    */
   public function doResetPassword(Request $request)
   {


      //    $validator = Validator::make($request->all(), [

      // 'token'	=> ['required'],
      // 'password' => ['required','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{1,}$/u'],
      // 'password_confirmation' => ['required']
      //    ]);

      $validator = Validator::make($request->all(), User::$_rules['changePassword']);

      if ($validator->fails()) {
         $error_msg = trans('auth.alerts.wrong_password_reset');
         return redirect('auth/reset/' . $request->token)->withErrors($validator)->withInput()->with('error', $error_msg);
      }

      $email = PasswordReminder::where('token', $request->token)->first();

      if ($email) {
         $user = User::where('email', $email->email)->first();
         $user->password = Hash::make($request->password);
         $user->password_changed_at = now();

         if ($user->save()) {
            PasswordReminder::where('email', $email->email)->where('token', $request->token)->delete();
            $notice_msg = trans('auth.alerts.password_reset');
            UserHistory::log($user->id, 'password-reset');
            return redirect('/')->with('notice', $notice_msg);
         } else {
            $error_msg = trans('auth.alerts.wrong_password_reset');
            return redirect('auth/reset/' . $request->token)->withInput()->with('error', $error_msg);
         }
      } else {
         $error_msg = trans('auth.alerts.wrong_token');
         return redirect('auth/reset/' . $request->token)->withInput()->with('error', $error_msg);
      }
   }



   /**
    * Log the user out of the application.
    *
    */
   public function logout()
   {

      if (auth()->check()) {
         UserHistory::log(auth()->user()->id, 'sign-out');
      }
      auth()->logout();
      session()->flush();

      // Save session before redirect
      // Added by zayid 7/6/2023
      session()->save();

      return redirect('/');
   }

   /**
    * Log the user out of the application via mobile
    * @return response
    */
   public function mobileLogout()
   {
      if (auth()->check()) {
         UserHistory::log(auth()->user()->id, 'sign-out');
      }
      Confide::logout();
      return 'ok';
   }

   /**
    * Show 2FA verification form
    */
   public function show2FA()
   {
      if (!session('2fa_pending_user_id')) {
         return redirect('/auth/login')->with('error', 'Sila log masuk terlebih dahulu.');
      }

      return view('auth.2fa');
   }

   /**
    * Verify the TOTP code (or a recovery code) and complete login.
    */
   public function verify2FA(Request $request)
   {
      $request->validate([
         'code' => 'required|string|max:64',
      ]);

      $userId = session('2fa_pending_user_id');

      if (empty($userId)) {
         return redirect('/auth/login')->with('error', 'Sesi telah tamat. Sila log masuk semula.');
      }

      $user = User::find($userId);

      if (!$user) {
         session()->forget(['2fa_pending_user_id', '2fa_pending_remember']);
         return redirect('/auth/login')->with('error', 'Pengguna tidak dijumpai.');
      }

      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || !$twoFactor->isConfirmed()) {
         session()->forget(['2fa_pending_user_id', '2fa_pending_remember']);
         return redirect('/auth/login')->with('error', 'Pengesahan dua faktor tidak aktif untuk akaun ini.');
      }

      // Rate limiting is persisted on the row, not the session: the session resets
      // every time credentials are re-submitted, which would hand an attacker a
      // fresh attempt budget against the same secret.
      if ($twoFactor->isLocked()) {
         $minutesLeft = max(1, now()->diffInMinutes($twoFactor->locked_until));
         return redirect()->route('2fa.verify')
            ->with('error', 'Terlalu banyak percubaan. Sila cuba lagi dalam ' . $minutesLeft . ' minit.');
      }

      $submitted = trim($request->input('code'));
      $isRecoveryCode = !preg_match('/^\d{6}$/', $submitted);
      $verified = false;
      $usedRecoveryCode = false;

      if ($isRecoveryCode) {
         $candidates = TwoFactorRecoveryCode::where('user_id', $user->id)->unused()->get();

         foreach ($candidates as $candidate) {
            if (Hash::check(strtoupper($submitted), $candidate->code_hash)) {
               $candidate->used_at = now();
               $candidate->save();
               $verified = true;
               $usedRecoveryCode = true;
               break;
            }
         }
      } else {
         $verified = (new Google2FA())->verifyKey($twoFactor->secret, $submitted);
      }

      if (!$verified) {
         $settings = TwoFactorSetting::current();
         $twoFactor->failed_attempts = $twoFactor->failed_attempts + 1;

         if ($twoFactor->failed_attempts >= $settings->max_failed_attempts) {
            $twoFactor->failed_attempts = 0;
            $twoFactor->locked_until = now()->addMinutes($settings->lockout_minutes);
            $twoFactor->save();

            TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_LOCKED_OUT, null, [
               'locked_until' => $twoFactor->locked_until->toDateTimeString(),
            ]);

            return redirect()->route('2fa.verify')
               ->with('error', 'Terlalu banyak percubaan. Akaun dikunci selama ' . $settings->lockout_minutes . ' minit.');
         }

         $twoFactor->save();

         return redirect()->route('2fa.verify')->with('error', 'Kod pengesahan tidak betul.');
      }

      // Verified - clear the counters and establish the real session.
      $twoFactor->failed_attempts = 0;
      $twoFactor->locked_until = null;

      $remember = (bool) session('2fa_pending_remember');
      session()->forget(['2fa_pending_user_id', '2fa_pending_remember']);

      auth()->login($user, $remember);
      session()->save();

      UserHistory::log($user->id, 'sign-in');

      if ($usedRecoveryCode) {
         $remaining = TwoFactorRecoveryCode::where('user_id', $user->id)->unused()->count();
         TwoFactorAuditLog::record($user->id, TwoFactorAuditLog::EVENT_RECOVERY_USED, null, [
            'remaining' => $remaining,
         ]);
         session()->flash('warning', 'Anda telah menggunakan kod pemulihan. Baki kod: ' . $remaining . '.');
      }

      $response = $this->redirectForUser($user);

      // "Remember this device" skips the challenge on this browser until it expires.
      if ($request->boolean('remember_device')) {
         $plainToken = \Illuminate\Support\Str::random(60);
         $days = TwoFactorSetting::current()->remember_device_days;

         $twoFactor->remember_token = hash('sha256', $plainToken);
         $twoFactor->remember_expires_at = now()->addDays($days);
         $twoFactor->save();

         return $response->withCookie(
            cookie('2fa_remember', $plainToken, $days * 24 * 60, null, null, null, true)
         );
      }

      $twoFactor->save();

      return $response;
   }

   /**
    * Checks the "remember this device" cookie against the stored token hash.
    */
   protected function hasValidRememberDeviceCookie(Request $request, User $user): bool
   {
      $plainToken = $request->cookie('2fa_remember');

      if (empty($plainToken)) {
         return false;
      }

      $twoFactor = $user->twoFactorAuth;

      if (!$twoFactor || empty($twoFactor->remember_token) || is_null($twoFactor->remember_expires_at)) {
         return false;
      }

      if ($twoFactor->remember_expires_at->isPast()) {
         return false;
      }

      return hash_equals($twoFactor->remember_token, hash('sha256', $plainToken));
   }

   /**
    * Post-login destination. Shared by doLogin() and verify2FA() so the two
    * paths cannot drift apart.
    */
   protected function redirectForUser(User $user)
   {
      if ($user->hasRole('Vendor')) {
         if (is_null($user->vendor)) {
            auth()->logout();
            session()->flash('error', 'Akaun anda mempunyai masalah.<br>Sila berhubung dengan Bahagian Teknologi Maklumat di <u>tenderadmin@selangor.gov.my</u> dan nyatakan alamat emel <b>(' . $user->email . '</b>) yang digunakan.');
            return redirect('/auth/login');
         }

         if (!$user->vendor->completed)
            return redirect('register/company');
         elseif (!$user->vendor->registration_paid)
            return redirect('register/payment');
         else
            return redirect('dashboard');
      } elseif ($user->can('Vendor:list')) {
         return redirect('vendors');
      } else if ($user->hasRole('Admin')) {
         return redirect()->route('dashboard.hq');
      } else {
         return redirect()->route('dashboard', ['id' => $user->organization_unit_id]);
      }
   }

   public function __construct()
   {

      // parent::__construct();
      // Config::set('former::TwitterBootstrap3.labelWidths', [
      //    'large' => 4,
      //    'small' => 4,
      // ]);
      // Config::set('former::TwitterBootstrap3.viewports', [
      //    'large'  => 'lg',
      //    'medium' => 'md',
      //    'small'  => 'sm',
      //    'mini'   => 'xs',
      // ]);
      // View::share('controller', 'AuthController');
      // Asset::push('js', 'login');
      // Asset::push('css', 'login');
   }
}
