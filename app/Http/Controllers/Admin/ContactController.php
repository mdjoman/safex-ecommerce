<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        return view('admin.contact.index', compact('contact'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'required|email',
            'google_map_embed_url' => 'nullable|url'
        ]);

        Contact::updateOrCreate(
            ['id' => 1],
            $request->all()
        );

        return redirect()->route('admin.contact.index')->with('success', 'Contact information updated successfully.');
    }
}
