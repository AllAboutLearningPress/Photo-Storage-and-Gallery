<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class AccountController extends Controller
{
    public function index()
    {
        return Inertia::render('Account/Account');
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


        $updatedData = ['name' => $data->name];
        if (
            array_key_exists('current_password', $data) &&
            Auth::guard('web')->validate([
                'email' => $request->user()->email,
                'password' => $request->current_password,
            ])
        ) {
            $updatedData['password'] =  Hash::make($data['password']);
            $updatedData['remember_token'] =  Str::random(60);
        }
        $request->user->update($updatedData);
        return redirect()->back();
    }
}
