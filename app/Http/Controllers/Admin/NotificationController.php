<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;    // <-- import Controller induk
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 📥 Ambil semua notifikasi (terurut dari terbaru)
    public function index()
    {
        $notifications = Notification::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    // 📄 Detail notifikasi berdasarkan ID
    public function show($id)
    {
        $notification = Notification::find($id);

        if (! $notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $notification,
        ]);
    }

    // ✅ Tandai sebagai dibaca
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (! $notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read',
            'data' => $notification,
        ]);
    }

    // 🗑️ Hapus notifikasi
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (! $notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification deleted successfully',
        ]);
    }
}
