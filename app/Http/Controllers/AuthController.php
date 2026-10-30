<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>'required|email','password'=>'required']);
        if (!auth()->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email'=>'Email atau password tidak sesuai.'])->withInput();
        }
        $request->session()->regenerate();
        return auth()->user()->role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('home');
    }
    public function showRegister() { return view('auth.register'); }
    public function register(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8|confirmed',
        ]);
        $user = \App\Models\User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>$data['password'],'role'=>'traveler']);
        auth()->login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success','Akun wisatawan berhasil dibuat.');
    }
    public function logout(Request $request) {
        auth()->logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
