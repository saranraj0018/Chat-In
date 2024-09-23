<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function adminAuthenticate(Request $request): \Illuminate\Http\RedirectResponse
    {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->passes()){
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        if (!Auth::guard('admin')->attempt(['email' => $request['email'],
            'password' => $request['password']], $request->get('remember'))){
            return redirect()->route('admin.login')->with('error','Either Email/Password is incorrect');
        }

        $admin = Auth::guard('admin')->user();
        if ($admin->role != 1){
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error','You are not authorized to access');
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
