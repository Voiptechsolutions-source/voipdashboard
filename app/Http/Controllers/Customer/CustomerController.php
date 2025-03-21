<?php

namespace App\Http\Controllers\Customer; // ✅ Updated namespace

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                    return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '">Edit</button>';
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

    // ✅ Update Customer Data
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'contact_no' => 'nullable|string|max:15',
        ]);

        $customer->update($request->all());

        return response()->json(['success' => 'Customer updated successfully.']);
    }

    // ✅ Delete Customer
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete(); // Delete customer
        return response()->json(['message' => 'Customer deleted successfully']);
    }

}
