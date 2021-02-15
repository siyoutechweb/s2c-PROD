<?php namespace App\Http\Controllers;
//use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

//nuse App\ResetsPasswords;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\License;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Password;
use  Illuminate\Notifications\Notification;
//use Illuminate\Auth\Notifications\ResetPassword;
class PasswordController extends Controller
{
    //use ResetsPasswords;

    public function __construct()
    {
        $this->broker = 'users';
    }
    public function sendResetEmail(Request $request)
{

        // I will assueme that you already have $email variable
        $user = User::where('email', $request->email)->first();
    if ( !$user ) return response()->json(['code'=>0]);

    //create a new token to be sent to the user. 
    DB::table('password_resets')->insert([
        'email' => $request->email,
        'token' => str_random(60), //change 60 to any length you want
        'created_at' => Carbon::now()
    ]);

    $tokenData = DB::table('password_resets')
    ->where('email', $request->email)->first();

   $token = $tokenData->token;
   $email = $request->email; // or $email = $tokenData->email;

        $response = Password::sendResetLink(['email' => $email], function (Message $message) {
            $message->subject($this->getEmailSubject());
        });
//         $response = \Illuminate\Support\Facades\Password::broker()->sendResetLink(['email' => $email]);
// $ok = $response == \Illuminate\Support\Facades\Password::RESET_LINK_SENT;

        switch ($response) {
            case Password::RESET_LINK_SENT:
                dump('We have e-mailed your password reset link!');
            case Password::INVALID_USER:
                dump('We can\'t find a user with that e-mail address.');
        }
}
    public function sendPasswordResetToken(Request $request)
{
    $user = User::where ('email', $request->email)-first();
    if ( !$user ) return response()->json(['code'=>0]);

    //create a new token to be sent to the user. 
    DB::table('password_resets')->insert([
        'email' => $request->email,
        'token' => str_random(60), //change 60 to any length you want
        'created_at' => Carbon::now()
    ]);

    $tokenData = DB::table('password_resets')
    ->where('email', $request->email)->first();

   $token = $tokenData->token;
   $email = $request->email; // or $email = $tokenData->email;

   /**
    * Send email to the email above with a link to your password reset
    * something like url('password-reset/' . $token)
    * Sending email varies according to your Laravel version. Very easy to implement
    */
}
/**
 * Assuming the URL looks like this 
 * http://localhost/password-reset/random-string-here
 * You check if the user and the token exist and display a page
 */

public function showPasswordResetForm($token)
{
    $tokenData = DB::table('password_resets')
    ->where('token', $token)->first();

    if ( !$tokenData ) return redirect()->to('home'); //redirect them anywhere you want if the token does not exist.
    return view('passwords.show');
}
public function resetPassword(Request $request)
 {
     //some validation
    
    $token = $request->input('token');
     $password = $request->password;
     $tokenData = DB::table('password_resets')
     ->where('token', $token)->first();
     //echo $tokenData;

     $user = User::where('email', $tokenData->email)->first();
     if ( !$user ) return response()->json(['code'=>0,"msg"=>"no user found"]); //or wherever you want

     $user->password = Hash::make($password);
     $user->update(); //or $user->save();
     DB::connection('b2b')->table('users')->where('email',$tokenData->email)->update(['password'=>Hash::make($password)]);

     //do we log the user directly or let them login and try their password for the first time ? if yes 
     Auth::login($user);

    // If the user shouldn't reuse the token later, delete the token 
    DB::table('password_resets')->where('email', $user->email)->delete();

    //redirect where we want according to whether they are logged in or not.
 }
 /// working

 public function sendPasswordResetEmail(Request $request){
    // If email does not exist
    if(!$this->validEmail($request->email)) {
        return response()->json([
            'message' => 'Email does not exist.'
        ], Response::HTTP_NOT_FOUND);
    } else {
        // If email exists
        $this->sendMail($request->email);
        return response()->json([
            'message' => 'Check your inbox, we have sent a link to reset email.'
        ], Response::HTTP_OK);            
    }
}


public function sendMail($email){
    $token = $this->generateToken($email);
    Mail::to($email)->send(new SendMail($token));
}

public function validEmail($email) {
   return !!User::where('email', $email)->first();
}

public function generateToken($email){
  $isOtherToken = DB::table('password_resets')->where('email', $email)->first();

  if($isOtherToken) {
    return $isOtherToken->token;
  }

  $token = Str::random(80);;
  $this->storeToken($token, $email);
  return $token;
}

public function storeToken($token, $email){
    DB::table('password_resets')->insert([
        'email' => $email,
        'token' => $token,
        'created' => Carbon::now()            
    ]);
}
// public function passwordResetProcess(UpdatePasswordRequest $request){
//     return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
//   }

//   // Verify if token is valid
//   private function updatePasswordRow($request){
//      return DB::table('recover_password')->where([
//          'email' => $request->email,
//          'token' => $request->passwordToken
//      ]);
//   }

//   // Token not found response
//   private function tokenNotFoundError() {
//       return response()->json([
//         'error' => 'Either your email or token is wrong.'
//       ],Response::HTTP_UNPROCESSABLE_ENTITY);
//   }

//   // Reset password
//   private function resetPassword($request) {
//       // find email
//       $userData = User::whereEmail($request->email)->first();
//       // update password
//       $userData->update([
//         'password'=>bcrypt($request->password)
//       ]);
//       // remove verification data from db
//       $this->updatePasswordRow($request)->delete();

//       // reset password response
//       return response()->json([
//         'data'=>'Password has been updated.'
//       ],Response::HTTP_CREATED);
//   }    
}