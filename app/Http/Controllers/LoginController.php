<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // 🔹 Show login page
    public function index()
    {
        return view('login');
    }

    // 🔹 Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            
            // ✅ SAVE SESSION
            session([
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return redirect('/employees')->with('success','Login successful!');
        }

        return back()->with('error','Invalid email or password');
    }

    // 🔹 Logout
    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success','Logged out successfully');
    }
}