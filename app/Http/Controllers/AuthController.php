<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function loginUser()
    {
        return Inertia::render('login', [
            'logo' => asset('images/elsabah.png'),
        ]);
    }
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        return redirect()->route('categories.index');
    }
}
