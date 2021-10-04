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
        dd($request);
        $data = $request->validate([
            'name' => 'required|string|max:128',
            // 'email' => 'required|email',
            'currentPassword' => 'string',
            'password' => 'string|required_with:currentPassword',
            'passwordConfirmation' => 'string|required_with:password'
        ]);
        dd($data);

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
