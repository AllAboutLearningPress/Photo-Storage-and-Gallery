<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\Invitation;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mail;
use URL;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Invitation/Index', [
            'invitations' => Invitation::where('invited_by', "=", $request->user()->id)->cursorPaginate(30),
            'title' => 'Sent Invitations'
        ]);
    }

    public function sendInvite(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $invitation = Invitation::where('email', "=", $data['email'])->first();
        $user = User::where('email', $data['email'])->count();

        // only create new invitation if no user or invitation
        // found with the provided email
        if (!$invitation && !$user) {
            // invitation not found and no user created with this email
            $invite_code =  bin2hex(random_bytes(16)); //;
            $invitation = Invitation::create([
                'email' => $data['email'],
                'code' => $invite_code,
                'invited_by' => $request->user()->id,
                'is_accepted' => false,
            ]);
        }

        // invite will be valid for 7 days
        $invite_url = URL::temporarySignedRoute(
            'invitations.accept_invite',
            now()->addDays(7),
            ['invite_code' => $invitation->code]
        );

        //$invite_url = route('invitations.accept_invite', $invitation->code);
        Mail::to($data['email'])->send(new InviteMail($request->user()->name, $invite_url));
        return response($status = 200);
    }

    /**
     * Shows the signup page for users with valid invite link
     */
    public function acceptInvite(Request $request, $invite_code)
    {
        $invite = Invitation::where([
            ['code', '=', $invite_code],
            ['is_accepted', '=', false]
        ])->firstOrFail();

        if ($invite) {
            return view('auth.signup')
                ->with('code', $invite_code)
                ->with('email', $invite->email);
        }
        return redirect(route('login'));
    }


    public function signup(Request $request)
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
            'code' => ['required', 'string', 'min:512'],

        ]);
        $invite = Invitation::where([['email', '=', $data['email']], ['code', "=", $data['code']]])->first();
        if ($invite) {
            $invite->update(['is_accepted' => true]);
            $user = User::create([
                'name' => $data['name'],
                'email' => $invite->email,
                'password' => Hash::make($data['password']),
            ]);
            Auth::login($user);
            return redirect(route('home'));
        }
        return redirect(route('login'));
    }

    /**
     * Deletes an Invite
     *  */
    public function deleteInvite(Request $request, $id)
    {
        $invite = Invitation::where('id', '=', $id)->firstOrFail();
        if (!$invite->is_accepted) {
            $invite->delete();
            return response($status = 200);
        }
        return abort(404);
    }
}
