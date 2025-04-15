<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadsApiController extends Controller
{
    // API to store customer leads
    public function saveCustomerLead(Request $request)
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
                'received_token' => $decodedToken,
                'expected_token' => $validToken,
            ]);
            return response()->json(['error' => 'Invalid Token'], 401);
        }

        // Step 1: Validate Input Fields
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|min:8|max:12',
            'email' => 'required|email|max:255',
            'services' => 'nullable|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Step 2: Check if email already exists
        $existingLead = Lead::where('email', $request->email)->first();
        if ($existingLead) {
            return response()->json([
                'error' => 'Email already exists',
                'message' => 'Email Address ' . $request->email . ' is already Exist'
            ], 409);// 409 Conflict for duplicate resource
        }

        // Step 3: Store customer lead
        try {
            $customer = Lead::create([
                'full_name' => $request->full_name,
                'country_code' => $request->country_code,
                'contact_no' => $request->contact_no,
                'email' => $request->email,
                'number_of_users' => $request->number_of_users,
                'service_name' => $request->services,
                'service_type' => $request->service_type,
                'source' => $request->source,
                'status' => '2',
                'message' => $request->message,
                'raw_data' => json_encode($request->all()),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer lead saved successfully',
                'data' => $customer
            ], 201);
        } catch (\Exception $e) {
            Log::error('Database Error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Database Error', 'message' => $e->getMessage()], 500);
        }
    }
}