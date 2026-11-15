<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function show()
    {
        $scholarships = Scholarship::active()->orderBy('sort_order')->get();
        return view('pages.admissions', compact('scholarships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:100'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'student_name'  => ['required', 'string', 'max:100'],
            'current_class' => ['nullable', 'string', 'max:50'],
            'desired_class' => ['required', 'string', 'max:10'],
            'inquiry_type'  => ['required', 'string', 'max:100'],
            'message'       => ['nullable', 'string'],
        ]);

        $subject = 'Admission Inquiry - ' . $data['inquiry_type'];
        $body    = "Student Name: {$data['student_name']}\n"
                 . "Current Class: " . ($data['current_class'] ?? '-') . "\n"
                 . "Desired Class: {$data['desired_class']}\n\n"
                 . "Message:\n" . ($data['message'] ?? '');

        ContactMessage::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'subject'    => $subject,
            'message'    => $body,
            'status'     => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('status', "Your admission inquiry has been submitted successfully! We'll contact you within 24 hours.");
    }
}
