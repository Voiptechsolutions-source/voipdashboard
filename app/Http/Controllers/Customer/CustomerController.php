<?php

namespace App\Http\Controllers\Customer; // ✅ Updated namespace

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // ✅ Fetch Customers (Web + DataTables)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusClass = ($row->status == 2) ? "btn-warning" :
                                  (($row->status == 0) ? "btn-danger" : "btn-success");
                    $statusText = ($row->status == 2) ? "New Lead" :
                                  (($row->status == 0) ? "Pending" : "Complete");

                    return '<button class="btn ' . $statusClass . ' btn-sm update-status" data-id="' . $row->id . '">' . $statusText . '</button>';
                })
                ->addColumn('ConvertLead', function ($row) {
                    $convertedClass = ($row->convertedlead == 1) ? "btn-secondary disabled" : "btn-success";
                    $convertedText = ($row->convertedlead == 1) ? "Already Converted" : "Convert Lead";

                    return '<button class="btn ' . $convertedClass . ' btn-sm convert-lead" data-id="' . $row->id . '">' . $convertedText . '</button>';
                })
                ->addColumn('view', function ($row) {
                    return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '">View</button>';
                })
                ->addColumn('Edit', function ($row) {
                    return '<button class="btn btn-warning btn-sm edit-lead" data-id="' . $row->id . '" data-toggle="modal" data-target="#editLeadModal">Edit</button>';
                })
                ->addColumn('Delete', function ($row) {
                    return '<button class="btn btn-danger btn-sm delete-row" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['status', 'ConvertLead', 'view','Edit','Delete']) // ✅ Ensures buttons display correctly
                ->toJson();
        }

        return view('customers.index');
    }

    // ✅ Show single customer
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
    //update status
    public function updateStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->description = $request->description;
        $customer->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
    }

    // Fetch customer data for editing
    public function edit($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            return response()->json($customer);
        }
        return response()->json(['message' => 'Customer not found!'], 404);
    }

    // ✅ Update Customer Data
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
            'status' => 'required|in:0,1,2' // 0 = Pending, 1 = Approved, 2 = Rejected
        ]);

        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Update fields
        $customer->full_name = $request->full_name;
        $customer->email = $request->email;
        $customer->country_code = $request->country_code;
        $customer->contact_no = $request->contact_no;
        $customer->address = $request->address;
        $customer->pincode = $request->pincode;
        $customer->service_name = $request->service_name;
        $customer->service_type = $request->service_type;
        $customer->number_of_users = $request->number_of_users;
        $customer->message = $request->message;
        $customer->comment = $request->comment;
        $customer->status = $request->status;
        $customer->updated_at = now(); // Update timestamp

        $customer->save(); // Save changes

        return response()->json(['message' => 'Customer updated successfully']);
    }

    // ✅ Delete Customer
    public function destroy(Request $request, $id) {
        // Ensure the user is logged in
        //dd(Auth::check());
        if (!Auth::guard('web')->check()) {
            return response()->json(['message' => 'Unauthorized. Please log in again.'], 401);
        }

        $user = Auth::guard('web')->user(); // Get the logged-in admin user
        $inputPassword = $request->input('password'); // Get password from request

        if (!$inputPassword) {
            return response()->json(['message' => 'Password is required.'], 400);
        }

        // Debugging logs
        \Log::info('Entered Password: ' . $inputPassword);
        \Log::info('Stored Hashed Password: ' . $user->password);

        // Check if the entered password matches the stored hash
        if (!Hash::check($inputPassword, $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        // Find and delete the customer
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }



}
