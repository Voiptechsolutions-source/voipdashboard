<?php

namespace App\Http\Controllers\Leads; // ✅ Updated namespace

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Constants\CommonParam;

class LeadsController extends Controller
{
    // ✅ Fetch Leads (Web + DataTables)
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Lead::query();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('status', function ($row) {
    //                 $statusClass = ($row->status == 2) ? "btn-warning" :
    //                               (($row->status == 0) ? "btn-danger" : "btn-success");
    //                 $statusText = ($row->status == 2) ? "New Lead" :
    //                               (($row->status == 0) ? "Pending" : "Complete");

    //                 return '<button class="btn ' . $statusClass . ' btn-sm update-status" data-id="' . $row->id . '">' . $statusText . '</button>';
    //             })
    //             ->filterColumn('status', function($query, $keyword) {
    //                 if (strtolower($keyword) === 'pending') {
    //                     $query->where('status', 0);
    //                 } elseif (strtolower($keyword) === 'new lead') {
    //                     $query->where('status', 2);
    //                 } elseif (strtolower($keyword) === 'complete') {
    //                     $query->where('status', 1);
    //                 }
    //             })
    //             ->addColumn('actions', function ($row) {
    //                 $viewBtn = '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '">View</button>';
    //                 $editBtn = '<button class="btn btn-warning btn-sm edit-lead" data-id="' . $row->id . '" data-toggle="modal" data-target="#editLeadModal">Edit</button>';
    //                 $deleteBtn = '<button class="btn btn-danger btn-sm delete-row" data-id="' . $row->id . '">Delete</button>';
    //                 $assignBtn = '<button class="btn btn-primary btn-sm assign-lead" data-id="' . $row->id . '" data-toggle="modal" data-target="#assignLeadModal">Assign</button>';

    //                 return '<div class="btn-group" role="group" aria-label="Actions">'
    //                     . $viewBtn . '&nbsp;' . $editBtn . '&nbsp;' . $assignBtn . '&nbsp;' . $deleteBtn .
    //                    '</div>';
    //             })
    //             ->rawColumns(['status', 'actions'])
    //             ->toJson();
    //     }

    //     return view('leads.index');
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $isSuperAdmin = $user->isSuperAdmin();

            // Superadmins see all leads, others see only assigned leads
            $data = $isSuperAdmin ? Lead::query() : Lead::where('assigned_to', Auth::id());

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusClass = ($row->status == 2) ? "btn-warning" :
                                  (($row->status == 0) ? "btn-danger" : "btn-success");
                    $statusText = ($row->status == 2) ? "New Lead" :
                                  (($row->status == 0) ? "Pending" : "Complete");

                    return '<button class="btn ' . $statusClass . ' btn-sm update-status" data-id="' . $row->id . '">' . $statusText . '</button>';
                })
                ->filterColumn('status', function($query, $keyword) {
                    if (strtolower($keyword) === 'pending') {
                        $query->where('status', 0);
                    } elseif (strtolower($keyword) === 'new lead') {
                        $query->where('status', 2);
                    } elseif (strtolower($keyword) === 'complete') {
                        $query->where('status', 1);
                    }
                })
                ->addColumn('assigned_to', function ($row) {
                    return $row->assigned_to; // Ensure this is included
                 })
                ->addColumn('assigned_username', function ($row) {
                    return $row->assignedTo ? $row->assignedTo->username : null; // Username
                })
                ->addColumn('actions', function ($row) {
                    return '<div class="btn-group" role="group" aria-label="Actions" data-assigned-to="' . $row->assigned_to . '"></div>';
                })
                ->rawColumns(['status', 'actions'])
                ->toJson();
        }

        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $permissions = $isSuperAdmin ? collect(['all' => true]) : ($user->role ? $user->role->permissions->pluck('pivot', 'page_name') : collect());
        $canView = $isSuperAdmin || ($permissions->has('leads') && $permissions['leads']->can_view);
        $canEdit = $isSuperAdmin || ($permissions->has('leads') && $permissions['leads']->can_edit);
        $canDelete = $isSuperAdmin || ($permissions->has('leads') && $permissions['leads']->can_delete);

        return view('leads.index', compact('canView', 'canEdit', 'canDelete', 'isSuperAdmin'));
    }
    // ✅ Show single lead
    public function show($id)
    {  
        $lead = Lead::findOrFail($id);
        $leadshistory = LeadHistory::join('leads', 'leads_history.lead_id', '=', 'leads.id')
        ->select(
            'leads_history.*', 
            'leads.full_name'
        )
        ->where('leads_history.lead_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();
        // Return JSON response for AJAX
        return response()->json([
            'id' => $lead->id,
            'description' => $lead->description,
            'status' => $lead->status,
            'leads_history' => $leadshistory
        ]);
    }

    
    public function viewfulldetails($id)
    {
        $lead = Lead::findOrFail($id);
        $leadshistory = LeadHistory::join('leads', 'leads_history.lead_id', '=', 'leads.id')
        ->select(
            'leads_history.*', 
            'leads.full_name'
        )
        ->where('leads_history.lead_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();
        $lead['leads_history'] = $leadshistory;
        return response()->json($lead); // Return the full lead object
    }


    // Fetch lead data for editing
    public function edit($id)
    {
        $lead = Lead::find($id);
        if ($lead) {
            return response()->json($lead);
        }
        return response()->json(['message' => 'Lead not found!'], 404);
    }

    // Fetch Sales and Admin Users (unchanged)
    public function getSalesAdmins()
    {
        $roles = Role::whereIn('name', ['sales', 'admin'])->pluck('id');
        $users = User::whereIn('role_id', $roles)->select('id', 'username', 'role_id')->with('role')->get();
        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'role_name' => $user->role->name
            ];
        });

        return response()->json(['users' => $users]);
    }

    // Assign Lead to User (unchanged)
    public function assign(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        $lead->assigned_to = $request->user_id;
        $lead->save();

        return response()->json(['message' => 'Lead assigned successfully']);
    }

    // ✅ Update Lead Status
    public function updateStatus(Request $request, $id)
    {
        // Debug specific inputs instead of the whole request
        \Log::info('Update Status Request Data:', [
            'id' => $id,
            'status' => $request->input('status'),
            'description' => $request->input('description'),
        ]);

        // Validate the input
        $request->validate([
            'status' => 'required|in:0,1,2', // Ensure valid status values
            'description' => 'nullable|string|max:255',
        ]);

        // Find the lead (returns 404 if not found)
        $lead = Lead::findOrFail($id);

        // Update the lead details
        $lead->status = $request->status;
        $lead->description = $request->description;

        // If status is "Complete" (1), set lead_id = id
        if ($request->status == "1") {
            $lead->lead_id = $id;
        }

        $lead->save(); // ✅ Save the changes
        // save lead history here
        $userDetail = $this->getCurrentUserDetails();   
        $addedBy = $userDetail->username;
        $this->saveLeadHistory($request, $addedBy);
        return response()->json([
            'message' => 'Status updated successfully!',
            'lead_id' => $lead->lead_id,
            'status' => $lead->status,
            'description' => $lead->description,
        ]);
    }

    // ✅ Update Lead Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'pincode' => 'nullable|string|max:10',
            'service_name' => 'nullable|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'number_of_users' => 'nullable|string|max:10',
            'message' => 'nullable|string',
            'comment' => 'nullable|string',
            'status' => 'required|in:0,1,2'
        ]);

        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        // Update fields
        $lead->full_name = $request->full_name;
        $lead->email = $request->email;
        $lead->country_code = $request->country_code;
        $lead->contact_no = $request->contact_no;
        $lead->address = $request->address;
        $lead->pincode = $request->pincode;
        $lead->service_name = $request->service_name;
        $lead->service_type = $request->service_type;
        $lead->number_of_users = $request->number_of_users;
        $lead->message = $request->message;
        $lead->comment = $request->comment;
        $lead->status = $request->status;
        $lead->source = $request->source;
        $lead->customer_description = $request->customer_description;
        $lead->description = $request->description;
        $lead->campaign_id = $request->campaign_id;
        $lead->form_id = $request->form_id;
        $lead->industry = $request->industry;
        

        $lead->save();

        return response()->json(['message' => 'Lead updated successfully']);
    }

    // ✅ Delete Lead
    public function destroy(Request $request, $id) {
        if (!Auth::guard('web')->check()) {
            return response()->json(['message' => 'Unauthorized. Please log in again.'], 401);
        }

        $user = Auth::guard('web')->user();
        $inputPassword = $request->input('password');

        if (!$inputPassword) {
            return response()->json(['message' => 'Password is required.'], 400);
        }

        if (!Hash::check($inputPassword, $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }

    // New create method
    public function create()
    {
        return view('leads.create');
    }

    // save manual lead data
    public function store(Request $request)
    {
        \Log::info('Store request received', $request->all());

        $request->validate([
            'full_name' => 'required|string|max:500',
            'email' => 'required|email|max:100|unique:leads,email',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|max:20',
            'address' => 'required|string',
            'pincode' => 'required|string|max:100',
            'service_name' => 'required|string|max:500',
            'service_type' => 'required|string|max:255', // Added service_type validation
            'industry' => 'required|string|max:255',
            'number_of_users' => 'required|string|max:500',
            'source' => 'required|in:Google,Facebook,CSV,Manual',
            'status' => 'required|in:0,1,2',
            'message' => 'required|string',
            'comment' => 'required|string',
            'description' => 'required|string',
            'customer_description' => 'required|string',
        ]);

        $leadData = $request->only([
            'full_name', 'email', 'country_code', 'contact_no', 'address',
            'pincode', 'service_name', 'service_type', 'industry', 'number_of_users',
            'source', 'status', 'message', 'comment', 'description', 'customer_description'
        ]);

        // Map 'services' to 'service_name'
        if ($request->has('services')) {
            $leadData['service_name'] = $request->input('services');
        }

        $lead = Lead::create($leadData);

        \Log::info('Lead created successfully with ID: ' . $lead->id);
        return redirect()->route('leads.index')->with('success', 'Lead added successfully!');
    }
    public function saveLeadHistory ($inputs, $addedBy) {
        $leadshistory = LeadHistory::create([
            'status' => $inputs['status'],
            'comment' => $inputs['description'],
            'lead_id' => $inputs['id'],
            'added_by' => $addedBy,
            'is_deleted' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if ($leadshistory) {
            return true;
        } 
        return false;
    }
    
}
