<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Create admin notifications for all admins
        try {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_contact_message',
                    'title' => 'New Contact Message',
                    'message' => "New message from {$validated['name']} ({$validated['email']}): {$validated['subject']}",
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to create admin notifications for contact message.', [
                'contact_message_id' => $contactMessage->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Your message has been sent successfully. Our team will respond shortly.');
    }
}
