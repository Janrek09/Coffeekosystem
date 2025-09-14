<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // Show notifications for the logged-in user
    public function show()
    {
        // Fetch notifications for the authenticated user
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->get();
        
        // Pass notifications data to the view
        return view('notifications', compact('notifications'));
    }
}