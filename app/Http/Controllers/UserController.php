<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\SubRole;
use App\Http\Controllers\SendMailController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStatus;

class UserController extends Controller
{
    public function index (){

        $users = User::with(['role', 'subrole','user_stat'])->whereIn("status", [1,3,4])->get();

        return view('pages.hr.users.index', compact('users'));
    }

    public function create(){

        $roles = map_options(Role::class, 'id', 'name');

        //1:1 subrole, exclude active subrole
        $excludedSubRoles = User::where('status', 1) ->whereNotNull('sub_role')->pluck('sub_role')->toArray();

        $subroles = SubRole::whereNotIn('id', $excludedSubRoles)->get()->map(function ($subrole) {
            return [
                'id' => $subrole->id,
                'name' => $subrole->description,
            ];
        });

        return view('pages.hr.users.create', compact('roles', 'subroles'));

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,id',
            'subrole' => 'nullable|exists:sub_roles,id',
            'emailaddress' => 'required|email|unique:users,email|max:255'
        ]);

        if ($validator->fails()) {
            return back()->with('error', implode('<br>', $validator->errors()->all()));
        }

        $randomPassword = Str::random(8);
        $reg = User::create([
            'name' => $request->name,
            'email' => $request->emailaddress,
            'password' => Hash::make($randomPassword),
            'role_id' => $request->role,
            'sub_role' => $request->subrole ?? null,
            'status' => 1
            
        ]);
        $email = $request->emailaddress;
        $subject = "Welcome to Our Service - Your Login Credentials";
        $body_messge = "
            <p>Dear $request->name,</p>
            <p>We are pleased to inform you that your account has been successfully created by your HR team.</p>
            <p>Below are your account details:</p>
            <ul>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Name:</strong> $request->name</li>
                <li><strong>Password:</strong> $randomPassword</li>
            </ul>
            <p>You can now log in to your account using the provided credentials.</p>
            <p>If you did not request an account, please disregard this email.</p>
            <p>Best regards,</p>
            <p>The WBEO Team</p>
            ";

        if($reg){

            $SendMailController = new SendMailController();
            try{
                $SendMailController->send_email($email,$subject,$body_messge);
            }catch(\Exception $e){
                return redirect()->back()->with('error', 'Unable to send email. Please try again later');
            }
           
        }

        return back()->with('success', 'Sucessfully Added User!');
    }

    public function details($id)
    {
        $userDetails = User::find($id);

        $roles = map_options(Role::class, 'id', 'name');
        $user_stats = map_options(UserStatus::class, 'id', 'description')->whereIn('id', [1,4]);

        $current_stat = UserStatus::where('id',$userDetails->status)->get();
        // dd($current_stat);
        $excludedSubRoles = User::where('status', 1)->whereNotNull('sub_role')
        ->where('sub_role', '!=', $userDetails->sub_role)
        ->pluck('sub_role')->toArray();
        // dd($excludedSubRoles);
        $subroles = SubRole::whereNotIn('id', $excludedSubRoles)->get()->map(function ($subrole) {
            return [
                'id' => $subrole->id,
                'name' => $subrole->description,
            ];
        });
        if ($userDetails) {
            return view('pages.hr.users.edit', compact('userDetails', 'roles', 'subroles','user_stats','current_stat'));
        }

        return redirect()->back()->with('error', 'User doesn\'t exist');
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role,
            'sub_role' => $request->subrole,
            'status' => $request->user_stats
        ]);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function disable($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User doesn\'t exist');
        }

        $user->update([
            'status' => 2,
        ]);

        return redirect()->route('users.index')->with('success', 'User account successfully disabled.');
    }


    public function change_password(){
        return view ('pages.auth.change_password');
    }

    public function process_change_password(Request $request){
        try{
            $user = Auth::user();
            if (Hash::check($request->password, $user->password)) {
                $user->update(['password' => bcrypt($request->new_password)]);
                return redirect()->back()->with('success', 'Password change successfuly.');
            }else{
                return redirect()->back()->with('error', 'Password not match.');
            }
        }
        catch(\Exception $e){
        return redirect()->back()->with('error', "Something went wrong");
        }
    }

    public function new_activate($id){
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User doesn\'t exist');
        }
        $user->update([
            'status' => 1,
        ]);
        return redirect()->route('users.index')->with('success', 'User account successfully activated.');
    }
}