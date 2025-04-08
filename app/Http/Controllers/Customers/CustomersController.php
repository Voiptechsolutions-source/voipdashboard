<?php
namespace App\Http\Controllers\Customers; // ✅ Updated namespace

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Import base Controller
use App\Models\Lead;


class CustomersController extends Controller
{
    public function index()
    {
        $leads = Lead::where('status', 1) // ✅ Only fetch leads with status = 1 (complete)
        ->select('id', 'full_name', 'email', 'contact_no', 'service_name')
        ->orderBy('created_at', 'desc') // 🔥 Order by latest leads first
        ->get();


        return view('customers.index', compact('leads'));
    }
}
