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

                    return '<button class="btn ' . $statusClass . ' btn-sm">' . $statusText . '</button>';
                })
                ->addColumn('action', function ($row) {
                    $convertedClass = ($row->convertedlead == 1) ? "btn-secondary disabled" : "btn-success";
                    $convertedText = ($row->convertedlead == 1) ? "Already Converted" : "Convert Lead";

                    return '<button class="btn ' . $convertedClass . ' btn-sm convert-lead" data-id="' . $row->id . '">' . $convertedText . '</button>';
                })
                ->addColumn('view', function ($row) {
                    return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '">View Full Detail</button>';
                })
                ->rawColumns(['status', 'action', 'view']) // ✅ Ensures buttons display correctly
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
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => 'Customer deleted successfully.']);
    }
}
