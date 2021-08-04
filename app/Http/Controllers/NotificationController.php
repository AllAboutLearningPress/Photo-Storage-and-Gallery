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
        $notifications =  Notification::where('user_id', Auth::id())->get();
        return Inertia::render("Notification/Notification", [
            'notifications' =>  genTempSrc($notifications, '/thumbnails/')
        ]);
    }
    public function seen(Request $request)
    {
        $data = $request->validate(['id' => ['integer', 'exists:notifications,id']]);
        Notification::where('id', $data['id'])->update(['seen' => true]);
        return response($status = 200);
    }
}
