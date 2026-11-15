<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Leadership;
use App\Models\SiteStat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumniController extends Controller
{
    public function show()
    {
        $featuredAlumni = Alumni::where('status', 'approved')
            ->whereNotNull('achievements')
            ->where('achievements', '!=', '')
            ->inRandomOrder()
            ->take(6)
            ->get();

        // CHANGED: pull MACOSA leadership messages + stats from DB
        $macosaMessages = Leadership::active()->category('macosa_message')->orderBy('sort_order')->get();
        $alumniStats    = SiteStat::active()->category('alumni')->orderBy('sort_order')->get();

        return view('pages.alumni', compact('featuredAlumni', 'macosaMessages', 'alumniStats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'          => ['required', 'string', 'max:100'],
            'maiden_name'        => ['nullable', 'string', 'max:100'],
            'email'              => ['required', 'email', 'max:100', Rule::unique('alumni', 'email')],
            'phone'              => ['nullable', 'string', 'max:20'],
            'graduation_year'    => ['required', 'integer', 'min:1990', 'max:' . (int) date('Y')],
            'class_level'        => ['required', 'string', 'max:10'],
            'current_profession' => ['nullable', 'string', 'max:100'],
            'company'            => ['nullable', 'string', 'max:100'],
            'location'           => ['nullable', 'string', 'max:100'],
            'achievements'       => ['nullable', 'string'],
            'willing_to_mentor'  => ['nullable'],
            'willing_to_speak'   => ['nullable'],
            'willing_to_donate'  => ['nullable'],
        ], [], [
            'full_name'       => 'full name',
            'graduation_year' => 'graduation year',
            'class_level'     => 'class level',
        ]);

        Alumni::create([
            'full_name'          => $data['full_name'],
            'maiden_name'        => $data['maiden_name'] ?? null,
            'email'              => $data['email'],
            'phone'              => $data['phone'] ?? null,
            'graduation_year'    => $data['graduation_year'],
            'class_level'        => $data['class_level'],
            'current_profession' => $data['current_profession'] ?? null,
            'current_employer'   => $data['company'] ?? null,
            'location'           => $data['location'] ?? null,
            'achievements'       => $data['achievements'] ?? null,
            'willing_to_mentor'  => (bool) $request->input('willing_to_mentor'),
            'willing_to_speak'   => (bool) $request->input('willing_to_speak'),
            'willing_to_donate'  => (bool) $request->input('willing_to_donate'),
            'status'             => 'pending',
        ]);

        return redirect()->route('alumni')->with('status', "Your MACOSA registration has been submitted successfully! We'll review and approve your profile within 48 hours.")->withFragment('register');
    }
}
