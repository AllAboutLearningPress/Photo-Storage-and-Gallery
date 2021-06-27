<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Mail\InviteMail;
use App\Models\Invitation;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mail;

class InvitationController extends Controller
{
    public function index()
    {
        return Inertia::render('Invitation/Index', ['title' => 'Sent Invitations']);
    }

    public function sendInvite(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $invitation = Invitation::where('email', "=", $data['email'])->first();
        if (!$invitation) {
            // invitation not found. Generate invitation
            $invite_code =  bin2hex(random_bytes(256)); //;
            $invitation = Invitation::create(['email' => $data['email'], 'code' => $invite_code]);
        }
        $invite_url = route('invitations.accept_invite', $invitation->code);
        Mail::to($data['email'])->send(new InviteMail($request->user()->name, $invite_url));
        return response($status = 200);
    }

    public function acceptInvite(Request $request, $invite_code)
    {
        $invite = Invitation::where('code', '=', $invite_code)->first();
        if ($invite) {
            return view('auth.signup');
        }
        abort(404);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'password' => [
                'required', 'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'  // must contain one special charecter
            ], // must contain a special character],
            'code' => ['required', 'string', 'digits:512'],

        ]);
        $invite = Invitation::where([['email', '=', $data['email']], ['code', "=", $data['code']]])->first();
        if ($invite) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
        $new_user = new CreateNewUser();
        $new_user->create($data);
    }
}
