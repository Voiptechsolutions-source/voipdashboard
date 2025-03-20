<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
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
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    // API to store customer leads
    public function store(Request $request)
    {
        // Check for Authorization header
        header('Access-Control-Allow-Origin: https://voip.voiptechsolutions.com');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept');
        if ($request->isMethod('options')) {
            return response()->json([], 200);
        }

        $authHeader = $request->header('Authorization');

        // Extract and decode Base64 token
        if ($authHeader && str_starts_with($authHeader, 'Basic ')) {
            $base64Token = substr($authHeader, 6);
            $decodedToken = trim(base64_decode($base64Token));
            $validToken = trim(base64_decode('dm9pcHRlY2hjcm0=')); // Expected: "voiptechcrm"

            if (strcmp($decodedToken, $validToken) !== 0) {
                return response()->json([
                    'error' => 'Invalid Token',
                    'received_token' => $decodedToken,
                    'expected_token' => $validToken,
                    'length_received' => strlen($decodedToken),
                    'length_expected' => strlen($validToken),
                ], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate input fields
        // $validator = Validator::make($request->all(), [
        //     'full_name' => 'required|string|max:500',
        //     'country_code' => 'required|string|max:10',
        //     'contact_no' => 'required|string|max:12|regex:/^\d+$/',
        //     'email' => 'required|email|max:255',
        //     'number_of_users' => 'required|string',
        //     'services' => 'required|string',
        //     'message' => 'nullable|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => 'Validation Error',
        //         'messages' => $validator->errors()
        //     ], 400);
        // }

        try {
            // Save customer data
            $customer = new Customer();
            $customer->full_name = $request->full_name;
            $customer->country_code = $request->country_code;
            $customer->contact_no = $request->contact_no;
            $customer->email = $request->email;
            $customer->number_of_users = $request->number_of_users;
            $customer->service_name = $request->services;
            $customer->source = $request->source;
            $customer->message = $request->message;
            $customer->raw_data = json_encode($request->all()); // ✅ Save all form data
            $customer->save();

            return response()->json(['success' => 'Customer lead saved successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database Error', 'message' => $e->getMessage()], 500);
        }
    }






}
