<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConvertLead; // ✅ Add this
//use App\Models\Lead; // ✅ Add this if needed
use Carbon\Carbon;

class LeadController extends Controller
{
    public function convertLead(Request $request)
    {
        $leadId = $request->input('lead_id');

        // Check if already converted
        if (ConvertLead::where('lead_id', $leadId)->exists()) {
            return response()->json(['message' => 'This lead is already converted.'], 400);
        }

        // Insert into ConvertLead table
        ConvertLead::create([
            'lead_id' => $leadId,
            'converted_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Lead converted successfully!']);
    }
}
?>