<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->update(['status' => 'read']);

        return view('admin.contact-messages.show', compact('message'));
    }

    public function respond(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:5000',
        ]);

        $message->update([
            'admin_response' => $validated['admin_response'],
            'status' => 'responded',
            'responded_at' => now(),
            'responded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Response sent successfully.');
    }

    public function delete(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }
}
