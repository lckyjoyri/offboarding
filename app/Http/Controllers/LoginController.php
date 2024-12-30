<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect to the correct page based on role
            switch ($user->role_id) {
                case 1:
                    return redirect()->route('users.index');
                case 2:
                    return redirect()->route('official_dashboard');
                case 3:
                    return redirect()->route('profile.index');
            }
        }
        return view('pages.auth.login');
    }

    public function register()
    {
        return view('pages.auth.register');
    }


}
