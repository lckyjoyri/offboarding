<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\SendMailController;
use Illuminate\Support\Str;
use App\Models\ClearanceRequest;


class AuthController extends Controller
{

    public function proccess_login(Request $request){
      try{
     $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    
            $user = Auth::user();
            if($user->status == 3){
                Auth::logout();
                return redirect()->back()->with('error', 'Contact HR for account activation.');
            }
            if($user->status == 1){
                session(['user_data' => $user]);
                switch($user->role_id){
                    case 1:   return  redirect()->intended(route('clearance.index'));
                    break;
                    case 2:   return  redirect()->intended(route('official_requests.index'));
                    break;
                    case 3:   return  redirect()->intended(route('employee_clearance.index'));
                    break;
                }

            }
          
        }
        return redirect()->back()->with('error', 'Invalid credentials');
    }catch(\Exception $e){
        return redirect()->back()->with('error', $e->getMessage());
    }

       
    }


    public function home()
    {
        $profile = User::where('id',auth()->id())->first();
        return view('pages.employee.profile.index',compact('profile'));
    }
    public function hr_dashboard()
    { 
        $users = User::with(['role', 'subrole'])->where("status", 1)->get();
        return view('pages.hr.users.index', compact('users'));
    }
    public function official_dashboard()
    {
       $clearanceRequest= ClearanceRequest::with(['clearance_purpose','statusDesc','comment_request'])->get();
       
        return view('pages.official.request-clearance.index', compact('clearanceRequest'));
    }



    public function proccess_register(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $reg = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => 3,
            'status' => 3

        ]);
        $email = $request->email;
        $subject = "Welcome to Our Service - Registration Confirmation";
        $body_messge = "
            <p>Dear $request->name,</p>
            <p>Thank you for registering with us!</p>
            <p>Here are your account details:</p>
            <ul>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Name:</strong> $request->name</li>
            </ul>
            <p>You can log in to your account using the credentials you provided during registration.</p>
            <p>If you did not register for an account, please ignore this email or contact our support team.</p>
            <p>Best regards,</p>
            <p>The WBEO Team</p>
            ";
       

        if($reg){
            $SendMailController = new SendMailController();
            try{
                $response = $SendMailController->send_email($email,$subject,$body_messge);
            }catch(\Exception $e){
                return redirect()->back()->with('error', 'Unable to send email. Please try again later');
            }

            return redirect()->back()->with('success', 'Successfully registered.');
        }else{
            return redirect()->back()->with('error', 'Registration failed');
        }
    }catch(\Exception $e){
        return redirect()->back()->with('error', 'Registration failed');
    }
      

    }

    public function logout(Request $request){

      
        Auth::logout();
         
        //remove alll session data
        $request->session()->invalidate();

        // generate the session token to protect against session fixation attacks
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Successfully logged out.');
    }

    public function forgot_password(){
        return view('pages.auth.forgotpass');
    }

    public function process_forgot_password(Request $request){

        try{
        $user = User::where('email', $request->email)
        ->where('name', $request->name)
        ->first();
        try{

            $randomPassword = Str::random(8);
            $email = $request->email;
            $subject = "Reset Password";
            $body_messge = "
                <p>Dear $request->name,</p>
                <p>We have generated a new password for your account. Below are your updated login details:</p>
                <ul>
                    <li><strong>Email:</strong> $email</li>
                    <li><strong>Password:</strong> $randomPassword</li>
                </ul>
                <p>Please use these credentials to log in to your account. For security reasons, we recommend updating your password immediately after logging in.</p>
                <p>Best regards,</p>
                <p>The WBEO Team</p>
                ";

        if($user){
            $user->update(['password' => bcrypt($randomPassword)]);
            $SendMailController = new SendMailController();
            $SendMailController->send_email($email,$subject,$body_messge);
        }
        
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Unable to send email. Please try again later');
        }

        return $user ? 
        redirect()->back()->with('success', 'Successfully password reset.') : 
        redirect()->back()->with('error', 'User not found');

    }catch(\Exception $e){
        return redirect()->back()->with('error', 'Something went wrong');
    }
       
    }
    




}
