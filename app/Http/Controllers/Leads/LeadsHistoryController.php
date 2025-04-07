<?php

namespace App\Http\Controllers\Leads; // ✅ Updated namespace

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use Illuminate\Http\Request;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


/**
 * Class LeadsHistoryController
 *    Controls the Leads History flow.
 */

class LeadsHistoryController extends Controller
{

    /**
     * Function index
     *  used for getting leads history on the basis of lead id
     * @author Raj <rahulsisodia82@gmail.com>
     * @param  string $id
     *  lead id     
     * @return array
     */

    public function index($id)
    {
        $leadshistory = LeadHistory::join('leads', 'leads_history.lead_id', '=', 'leads.id')
            ->select(
                'leads_history.*', 
                'leads.full_name'
            )
            ->where('leads_history.lead_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('leads.history', compact('leadshistory'));
    }
}
