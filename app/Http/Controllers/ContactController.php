<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Leadership;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        // CHANGED: pull FAQs and department contacts from DB
        $faqs = Faq::active()->orderBy('sort_order')->get();
        $departments = Leadership::active()->category('dept_contact')->orderBy('sort_order')->get();

        return view('pages.contact', compact('faqs', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        ContactMessage::create($data + [
            'status'     => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('status', 'Thanks — your message has been received. We will respond shortly.');
    }
}
