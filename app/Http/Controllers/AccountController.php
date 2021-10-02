<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        return Inertia::render('Account');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|email',
            'current_password' => 'string',
            'new_password' => 'string|required_with:current_password',
            'password_confirmation' => 'string|required_with:new_password'
        ]);


        $request->user();
    }
}
