<?php
namespace App\Http\Controllers\Customer; // Ensure this matches the folder structure

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Import base Controller
use App\Models\ConvertLead;
use App\Models\Customer;

class ConvertedLeadsController extends Controller
{
    public function index()
    {
        $leads = ConvertLead::join('customers', 'convert_leads.lead_id', '=', 'customers.id')
            ->select(
                'convert_leads.*', 
                'customers.full_name', 
                'customers.email', 
                'customers.contact_no',
                'customers.service_name'
            )
          ->get();

        return view('converted-leads.index', compact('leads'));
    }
}
