<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contacts.index', compact('messages'));
    }

    public function markRead(ContactMessage $contact)
    {
        $contact->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return back()->with('success', 'Message deleted.');
    }
}