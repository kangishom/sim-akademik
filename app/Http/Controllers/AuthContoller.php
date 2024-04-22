<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthContoller extends Controller
{
    public function registerForm(){
        return view('registerform');
    }

    public function process(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return  redirect('/coba');
    }

    public function login(){
        return view('login');
    }

    public function loginproses(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $rememberme = $request->has('remember_me') ? true : false; 
 
        if (Auth::attempt($credentials, $rememberme)) {
            $request->session()->regenerate();
 
            return redirect()->intended('/coba');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/coba');
    }

    public function forgotpassword(){
        return view('forgotpassword');
    }
}



