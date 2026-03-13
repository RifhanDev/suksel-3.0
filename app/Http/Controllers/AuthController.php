<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgotPassword;
use App\User;
use App\UserHistory;
use App\PasswordReminder;
use Hash;
use Auth;
use Mail;
use Log;

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

               // === 2FA COMMENTED ===
               // // Generate 2FA code
               // $twoFactorCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
               // $user->two_factor_code = $twoFactorCode;
               // $user->two_factor_expires_at = now()->addMinutes(10);
               // $user->save();

               // // Store user ID in session for 2FA verification
               // session()->put('2fa_user_id', $user->id);
               // session()->put('2fa_code', $twoFactorCode);

               // // Logout before 2FA verification
               // auth()->logout();

               // // Save session before redirect
               // session()->save();

               // // Redirect to 2FA verification page
               // return redirect('/auth/2fa/verify');
               // =====================

               session()->save();

               UserHistory::log($user->id, 'sign-in');

               // Redirect based on user role
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
         $user->confirmed = 1;
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

      UserHistory::log(Auth::user()->id, 'sign-out');
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
      UserHistory::log(Auth::user()->id, 'sign-out');
      Confide::logout();
      return 'ok';
   }

   /**
    * Show 2FA verification form
    */
   public function show2FA()
   {
      if (!session('2fa_user_id')) {
         return redirect('/auth/login')->with('error', 'Sila log masuk terlebih dahulu.');
      }

      return view('auth.2fa');
   }

   /**
    * Verify 2FA code and complete login
    */
   public function verify2FA(Request $request)
   {
      $request->validate([
         'code' => 'required|string|size:6'
      ]);

      $userId = session('2fa_user_id');

      if (empty($userId)) {
         return redirect('/auth/login')->with('error', 'Sesi telah tamat. Sila log masuk semula.');
      }

      $user = User::find($userId);

      $testCode = 123456;
      if ($request->code === $testCode) {
         $user = User::find($userId);
         $user->two_factor_code = null;
         $user->two_factor_expires_at = null;
         $user->save();
         session()->forget('2fa_user_id');
         session()->forget('2fa_code');
         return redirect('/auth/login')->with('error', 'Kod pengesahan telah tamat tempoh. Sila log masuk semula.');
      }

      // if (!$user) {
      //    session()->forget('2fa_user_id');
      //    session()->forget('2fa_code');
      //    return redirect('/auth/login')->with('error', 'Pengguna tidak dijumpai.');
      // }

      // // Check if code matches and hasn't expired
      // if ($user->two_factor_code !== $request->code) {
      //    return redirect('/auth/2fa/verify')->with('error', 'Kod pengesahan tidak betul.')->withInput();
      // }

      // if (now()->gt($user->two_factor_expires_at)) {
      //    $user->two_factor_code = null;
      //    $user->two_factor_expires_at = null;
      //    $user->save();
      //    session()->forget('2fa_user_id');
      //    session()->forget('2fa_code');
      //    return redirect('/auth/login')->with('error', 'Kod pengesahan telah tamat tempoh. Sila log masuk semula.');
      // }

      // Clear 2FA code
      $user->two_factor_code = null;
      $user->two_factor_expires_at = null;
      $user->save();

      // Clear session
      session()->forget('2fa_user_id');
      session()->forget('2fa_code');

      // Login the user
      auth()->login($user);

      // Save session before redirect
      session()->save();

      UserHistory::log($user->id, 'sign-in');

      // Redirect based on user role
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
