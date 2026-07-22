<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(12);

        return view('notifications.index', compact('notifications'));
    }

    public function show(string $notificationId): View
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
