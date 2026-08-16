<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('salesPerson')->latest()->get();
        $salesPeople = User::where('role', 'sales')->get();
        return view('admin.leads.index', compact('leads', 'salesPeople'));
    }

    public function show(Lead $lead)
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,converted,lost',
            'assigned_sales' => 'nullable|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $lead->update($request->all());

        return redirect()->route('admin.leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully.');
    }
}
