<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class ContactController extends Controller
{
    /**
     * Store a new contact message from public form
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validatedData);

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda melalui email.');
    }

    /**
     * Display listing of messages for admin
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        return view('contacts.index', compact('messages'));
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Pesan ditandai sebagai terbaca.');
    }

    /**
     * Delete a message
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        
        ActivityLogService::log('contact_message_delete', "Menghapus pesan dari {$message->name}");

        return redirect()->back()->with('success', 'Pesan berhasil dihapus.');
    }
}
