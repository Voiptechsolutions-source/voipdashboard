<?php

namespace App\Http\Controllers\Customer; // ✅ Updated namespace

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class CustomerApiController extends Controller
{
    // API to store customer leads
    public function savecustomerlead(Request $request)
    {
        // Handle preflight (CORS) requests
        if ($request->isMethod('options')) {
            return response()->json([], 200);
        }

        // Validate Authorization Header
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Decode and validate token
        $base64Token = substr($authHeader, 6);
        $decodedToken = trim(base64_decode($base64Token));
        $validToken = trim(base64_decode('dm9vcHRlY2hjcm0=')); // "voiptechcrm"

        if ($decodedToken !== $validToken) {
            Log::warning('Token validation failed', [
                'received_token_length' => strlen($decodedToken),
                'expected_token_length' => strlen($validToken),
            ]);
            return response()->json(['error' => 'Invalid Token'], 401);
        }

            // **Step 1: Validate Input Fields**
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'number_of_users' => 'required|integer|min:1',
            'services' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }
        // Store customer lead
        try {
            $customer = new Customer();
            $customer->full_name = $request->full_name;
            $customer->country_code = $request->country_code;
            $customer->contact_no = $request->contact_no;
            $customer->email = $request->email;
            $customer->number_of_users = $request->number_of_users;
            $customer->service_name = $request->services;
            $customer->service_type = $request->service_type;
            $customer->source = $request->source;
            $customer->status='2';
            $customer->message = $request->message;
            $customer->raw_data = json_encode($request->all());
            $customer->save();

            return response()->json([
                'status' => 'success', // Add this
                'success' => 'Customer lead saved successfully'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Database Error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Database Error', 'message' => $e->getMessage()], 500);
        }
    }
}
