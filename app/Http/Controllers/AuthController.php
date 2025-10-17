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

   public function doLogin(Request $request) {

      if(!is_null(session('attempt_again'))){
         Log::Debug('!is_null attempt_again');
         $now = time();
         if($now >= session('attempt_again')) {
            session()->forget('attempt');
            session()->forget('attempt_again');
         }
         else {
            $err_msg = trans('auth.alerts.too_many_attempts');
            return redirect('/')->withInput($request->except('password'))->with('error', $err_msg);
         }
      }

      

      if(is_null(session('attempt_again'))){
         Log::Debug('is_null attempt_again');
         if(is_null(session('attempt'))){
            session()->put('attempt', 0);
         }
    
         if(session('attempt') > 5){
            $err_msg = trans('auth.alerts.too_many_attempts');
            return redirect('/')->withInput($request->except('password'))->with('error', $err_msg);
         }
         else{

            $user = User::where('username', $request->email)->where('password', md5($request->password))->orWhere('password', Hash::make($request->password))->first();

            if($user) {
                  $user->password = Hash::make($request->password);
                  $user->save();
            }

            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {

               $user = auth()->user();

               session()->forget('attempt');

               // Save session before redirect
               // Added by zayid 7/6/2023
               session()->save();
               
               UserHistory::log($user->id, 'sign-in');

               if($user->hasRole('Vendor')) {

                  if(is_null($user->vendor)) {
                     auth()->logout();
                     session()->flash('error', 'Akaun anda mempunyai masalah.<br>Sila berhubung dengan Bahagian Teknologi Maklumat di <u>tenderadmin@selangor.gov.my</u> dan nyatakan alamat emel <b>(' . $user->email . '</b>) yang digunakan.');
                     return redirect('/');
                  }

                  if(!$user->vendor->completed)
                     return redirect('register/company');
                  elseif(!$user->vendor->registration_paid)
                     return redirect('register/payment');
                  else
                     return redirect('dashboard');
                   
               }
               elseif($user->can('Vendor:list'))
                  return redirect('vendors');
               else
                  return redirect('agencies/'.$user->organization_unit_id);
            }
            else {
               $attempt = session('attempt');
               session()->put('attempt', $attempt+= 1);

               if(session('attempt') > 5 ){
                  $attempt_again = time() + (5*60);
                  session()->put('attempt_again', $attempt_again);
                  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
                  $err_msg = trans('auth.alerts.too_many_attempts');
                  return redirect('/')->withInput($request->except('password'))->with('error', $err_msg);
               }
               else {
                  $err_msg = trans('auth.alerts.wrong_credentials');
                  return redirect('/')->withInput($request->except('password'))->with('error', $err_msg);
               }
            }

         }

      }

   }

   /**
   * Attempt to do login via mobile
   * @return response
   */
   public function mobileLogin() {
        
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
        	}
        	else {

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
   public function confirm($code) {

   	$user = User::where('confirmation_code', $code)->first();

   	if($user) {
   		// $user->confirmed = 1;
   		$user->save();
   		$notice_msg = trans('auth.alerts.confirmation');
            return redirect('/')->with('notice', $notice_msg);
   	}
   	else {
   		$error_msg = trans('auth.alerts.wrong_confirmation');
            return redirect('/')->with('error', $error_msg);
   	}
   }

   /**
   * Displays the forgot password form
   *
   */
   public function forgotPassword() {
   	return view('home.forgot_password');
   }

   /**
   * Attempt to send change password link to the given email
   *
   */
   public function doForgotPassword(Request $request) {

   		$user = User::where('email', $request->email)->first();

   		if($user) {
   			Mail::to($user)->send(new ForgotPassword($user));
   			UserHistory::log($user->id, 'password-forget');
   			$notice_msg = trans('auth.alerts.password_forgot'); 
            return redirect('/')->with('notice', $notice_msg);
   		}
   		else {
            $error_msg = trans('auth.alerts.wrong_password_forgot'); 
            return redirect('auth/forgot_password')->withInput()->with('error', $error_msg);
   		}
        	
   }

   /**
   * Shows the change password form with the given token
   *
   */
   public function resetPassword(Request $request, $token) {
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
   public function doResetPassword(Request $request) {


   //    $validator = Validator::make($request->all(), [
         
			// 'token'	=> ['required'],
			// 'password' => ['required','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{1,}$/u'],
			// 'password_confirmation' => ['required']
   //    ]);

   	$validator = Validator::make($request->all(), User::$_rules['changePassword']);

      if ($validator->fails()) {
         $error_msg = trans('auth.alerts.wrong_password_reset'); 
      	return redirect('auth/reset/'.$request->token)->withErrors($validator)->withInput()->with('error', $error_msg);
      }
      else {

        	$email = PasswordReminder::where('token', $request->token)->first();

        	if ($email) {
	        	$user = User::where('email', $email->email)->first();
	        	$user->password = Hash::make($request->password);

	        	if($user->save()) {
	        		PasswordReminder::where('email', $email->email)->where('token', $request->token)->delete();
	        		$notice_msg = trans('auth.alerts.password_reset'); 
	            UserHistory::log($user->id, 'password-reset');
	            return redirect('/')->with('notice', $notice_msg);
	        	}
	        	else {
	        		$error_msg = trans('auth.alerts.wrong_password_reset'); 
	            return redirect('auth/reset/'.$request->token)->withInput()->with('error', $error_msg);
	        	}
        	}

        	else {
        		$error_msg = trans('auth.alerts.wrong_token'); 
	         return redirect('auth/reset/'.$request->token)->withInput()->with('error', $error_msg);
        	}

      }

        
   }

   /**
   * Log the user out of the application.
   *
   */
   public function logout() {

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
   public function mobileLogout() {
        	UserHistory::log(Auth::user()->id, 'sign-out');
        	Confide::logout();
        	return 'ok';
   }

   public function __construct() {

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
