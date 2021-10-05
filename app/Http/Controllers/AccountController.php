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
        // session()->flash('success', ['password changed', 'name updated']);
        return Inertia::render('Account/Account');
    }

    public function update(Request $request)
    {
        //dd($request);
        $data = $request->validate([
            'name' => 'required|string|max:128',
            // 'email' => 'required|email',
            'currentPassword' => 'string|nullable',
            'password' => 'string|required_with:currentPassword',
            'passwordConfirmation' => 'string|required_with:password'
        ]);

        $flashMsgs = [];
        $updatedData = [];
        if ($data['name'] != $request->user()->name) {
            $updatedData['name'] = $data['name'];
            array_push($flashMsgs, 'Name Updated');
        }
        if (
            array_key_exists('currentPassword', $data) &&
            Auth::guard('web')->validate([
                'email' => $request->user()->email,
                'password' => $data['currentPassword'],
            ])
        ) {
            // dd($data);
            $updatedData['password'] =  Hash::make($data['password']);
            $updatedData['remember_token'] =  Str::random(60);
            array_push($flashMsgs, 'Password updated');
        }

        if (count($flashMsgs)) {
            session()->flash('success', $flashMsgs);
        }
        $request->user()->update($updatedData);
        return redirect(route('account.index'));
    }
}
