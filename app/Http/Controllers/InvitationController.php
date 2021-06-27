<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\Invitation;
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
        $invitation = Invitation::firstOrCreate(['email' => $data['email']]);
        Mail::to($data['email'])->send(new InviteMail($request->user()->name));
    }
}
