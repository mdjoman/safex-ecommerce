<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        return view('frontend.contact.index', compact('contact'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        // Create lead
        Lead::create([
            'lead_id' => 'LEAD-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'customer_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => 'Contact Form',
            'status' => 'new',
            'notes' => $request->message
        ]);

        // Send email notification
        try {
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($request->all()));
        } catch (\Exception $e) {
            // Log error but continue
        }

        return redirect()->route('contact.index')->with('success', 'Your message has been sent successfully.');
    }
}
