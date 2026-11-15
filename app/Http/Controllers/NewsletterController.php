<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'status'        => 'active',
                'ip_address'    => $request->ip(),
                'subscribed_at' => now(),
            ]
        );

        return back()->with('status', 'Thanks for subscribing!');
    }
}
