<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginUser()
    {
        if(Auth::check()){
            return redirect()->route('categories.index');
        }
        return Inertia::render('login', [
            'logo' => asset('images/elsabah.png'),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        return redirect()->route('categories.index');
    }

    public function dashboard(){
        return redirect()->route('categories.index');
    }
}
