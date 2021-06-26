<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function index()
    {
        return Inertia::render('Invitation/Index');
    }
}
