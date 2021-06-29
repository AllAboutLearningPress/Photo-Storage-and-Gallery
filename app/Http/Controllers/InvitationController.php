<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Mail\InviteMail;
use App\Models\Invitation;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mail;

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
        if (!$invitation) {
            // invitation not found. Generate invitation
            $invite_code =  bin2hex(random_bytes(256)); //;
            $invitation = Invitation::create([
                'email' => $data['email'],
                'code' => $invite_code,
                'invited_by' => $request->user()->id,
                'is_accepted' => false,
            ]);
        }
        $invite_url = route('invitations.accept_invite', $invitation->code);
        Mail::to($data['email'])->send(new InviteMail($request->user()->name, $invite_url));
        return response($status = 200);
    }

    public function acceptInvite(Request $request, $invite_code)
    {
        $invite = Invitation::where('code', '=', $invite_code)->first();
        if ($invite) {
            return view('auth.signup')->with('code', $invite_code);
        }
        abort(404);
    }
    public function signup(Request $request)
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
            'code' => ['required', 'string', 'min:512'],

        ]);
        $invite = Invitation::where([['email', '=', $data['email']], ['code', "=", $data['code']]])->first();
        if ($invite) {
            $invite->update(['is_accepted' => true]);
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            Auth::login($user);
            return redirect(route('home'));
        }
        return redirect(route('login'));
    }
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
