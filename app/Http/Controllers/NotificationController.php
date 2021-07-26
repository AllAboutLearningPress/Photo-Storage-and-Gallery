<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return Inertia::render("Notification/Index", [
            'notifications' => Notification::where('user_id', Auth::id())->get()
        ]);
    }
}
