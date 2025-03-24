<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\ConvertLead;

class SupportController extends Controller
{
    // Show the support records
    public function index()
    {
        $supportData = Support::join('convert_leads', 'supports.lead_id', '=', 'convert_leads.id')
            ->join('customers', 'convert_leads.lead_id', '=', 'customers.id')
            ->select(
                'supports.*', 
                'customers.full_name', 
                'customers.email', 
                'customers.contact_no'
            )
            ->get();

        return view('support.index', compact('supportData'));
    }

    // Store revenue in the support table
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:convert_leads,id',
            'notes' => 'nullable|string',
            'revenue_per_day' => 'required|numeric'
        ]);

        Support::create([
            'lead_id' => $request->lead_id,
            'notes' => $request->notes,
            'revenue_per_day' => $request->revenue_per_day
        ]);

        return response()->json(['message' => 'Revenue added successfully!']);
    }
}
