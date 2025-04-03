<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConvertLead;
use Carbon\Carbon;

class LeadController extends Controller
{
    // ✅ Convert Lead Function
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
            'created_at' => Carbon::now(),  // ✅ Use Carbon for consistency
            'updated_at' => Carbon::now(),
            'is_active' => true,  // ✅ Default active
            'is_delete' => false, // ✅ Default not deleted
        ]);

        return response()->json(['message' => 'Lead converted successfully!']);
    }
}

?>
