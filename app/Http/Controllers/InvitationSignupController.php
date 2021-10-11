<?php

namespace App\Http\Controllers;



use App\Models\Invitation;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use URL;

class InvitationSignupController extends Controller
{


    /**
     * Shows the signup page if invite_code is valid
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $invite_code)
    {

        $invite = Invitation::where([
            ['code', '=', $invite_code],
            ['is_accepted', '=', false]
        ])->firstOrFail();

        if ($invite) {
            return view('auth.signup')
                ->with('code', $invite_code)
                ->with('email', $invite->email)
                ->with('store_link', URL::temporarySignedRoute('invitations.signup.store', now()->addMinutes(20)));
        }
        return redirect(route('login'));
    }

    /**
     * Stores newly created user from invitation link
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'password' => [
                'required', 'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'  // must contain one special charecter
            ],
            'code' => ['required', 'string', 'min:32'],

        ]);
        $invite = Invitation::select()
            ->where([['code', "=", $data['code']], ['is_accepted', '=', false]])->first();
        if ($invite) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $invite->email,
                'password' => Hash::make($data['password']),
                'role_id' => $invite->role_id
            ]);
            $invite->update(['is_accepted' => true]);
            Auth::login($user);
            return redirect(route('home'));
        }
        return redirect(route('login'));
    }
}
