<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mark all notifications as read for the authenticated user
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        // Use the method correctly (it's a query builder method, not a relationship)
        $user->notifications()
             ->where('is_read', false)
             ->update([
                 'is_read' => true,
                 'read_at' => now(),
             ]);
        
        return response()->json(['success' => true]);
    }
}
