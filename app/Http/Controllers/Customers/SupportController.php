<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\Lead;

class SupportController extends Controller
{
    // Show the support records
    public function index()
    {
        $supportData = Support::join('leads', 'supports.lead_id', '=', 'leads.id')
            ->select(
                'supports.*', 
                'leads.full_name', 
                'leads.email', 
                'leads.contact_no'
            )
            ->get();

        return view('support.index', compact('supportData'));
    }

    // Store revenue in the support table
    public function store(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'lead_id' => 'required|exists:leads,id', 
            'notes' => 'nullable|string',
            'revenue_per_day' => 'required|numeric'
        ]);

        // ✅ Debug: Check if lead_id exists before inserting
        if (!Lead::where('id', $request->lead_id)->exists()) {
            return response()->json(['message' => 'Invalid lead_id. Lead not found.'], 400);
        }

        // ✅ Insert into supports table
        $support = Support::create([
            'lead_id' => $request->lead_id,
            'notes' => $request->notes,
            'revenue_per_day' => $request->revenue_per_day
        ]);

        return response()->json([
            'message' => 'Revenue added successfully!',
            'data' => $support
        ]);
    }

}

