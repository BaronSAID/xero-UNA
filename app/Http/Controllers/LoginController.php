<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{

    public function login() {
        validator(request()->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ])->validate();

        if (auth()->attempt(request()->only(['email', 'password']))) {
            $credentials = request()->only(['email', 'password']);
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            Auth::login($user);
            return redirect('/manage/xero');

        }

        return redirect()->back()->withErrors(['email' => 'Invalid Credentials!']);
    }

    public function logout(){
        auth()->logout();

        return redirect('/');
    }
}
