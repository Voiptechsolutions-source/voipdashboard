<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer; // Ensure you import the Customer model
use Illuminate\Support\Facades\Http;


class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Webhook received: ', $request->all());

        // Validate the incoming JSON
        $leadData = $request->json()->all();

        if (!$leadData) {
            return response()->json(['status' => 'error', 'message' => 'Invalid JSON'], 400);
        }

        // Extract lead details
        $lead_id = $leadData['lead_id'] ?? null;
        $form_id = $leadData['form_id'] ?? null;
        $campaign_id = $leadData['campaign_id'] ?? null;
        $source = "Google"; // Since it's from Google Ads

        // Extract user details
        $fields = [];
        foreach ($leadData['user_column_data'] ?? [] as $field) {
            if (isset($field['column_id']) && isset($field['string_value'])) {
                $fields[$field['column_id']] = $field['string_value'];
            }
        }

        $full_name = $fields['FULL_NAME'] ?? null;
        $email = $fields['WORK_EMAIL'] ?? null;
        $phone = $fields['WORK_PHONE'] ?? null;
        $company_name = $fields['COMPANY_NAME'] ?? null;

        // Extract country code from phone number (if available)
        $country_code = null;
        if ($phone && preg_match('/^\+(\d{1,4})/', $phone, $matches)) {
            $country_code = $matches[1];
        }

        // Insert lead into database
        try {
            Customer::create([
                'full_name' => $full_name,
                'email' => $email,
                'country_code' => $country_code,
                'contact_no' => $phone,
                'customer_description' => $company_name,
                'lead_id' => $lead_id,
                'campaign_id' => $campaign_id,
                'form_id' => $form_id,
                'source' => $source,
                'status' => 'Pending',
            ]);

            return response()->json(['status' => 'success', 'message' => 'Lead saved successfully']);
        } catch (\Exception $e) {
            Log::error('Database Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Database Error'], 500);
        }
    }

    public function facebookWebhook(Request $request)
    {
        // ✅ Fetch Access Token from .env
        $access_token = env('FACEBOOK_ACCESS_TOKEN');

        // ✅ Handle Facebook Webhook Verification
        if ($request->has('hub_mode') && $request->input('hub_mode') === 'subscribe') {
            if ($request->input('hub_verify_token') === env('FACEBOOK_VERIFY_TOKEN')) {
                return response($request->input('hub_challenge'), 200);
            } else {
                return response('Invalid verification token', 403);
            }
        }

        // ✅ Capture Webhook Payload
        $payload = $request->all();
        Log::info('Webhook Payload:', $payload);

        // ✅ Extract Lead ID
        $leadgen_id = $payload['entry'][0]['changes'][0]['value']['leadgen_id'] ?? null;

        if (!$leadgen_id) {
            Log::error('No Lead ID found in webhook payload.');
            return response()->json(['status' => 'error', 'message' => 'No Lead ID found'], 400);
        }

        // ✅ Check if Access Token is Set
        if (!$access_token) {
            return response()->json(['status' => 'error', 'message' => 'Access Token not set in ENV'], 500);
        }

        // ✅ Fetch Lead Data from Facebook Graph API
        $lead_url = "https://graph.facebook.com/v22.0/$leadgen_id?access_token=$access_token";
        $response = Http::get($lead_url);
        $lead_data = $response->json();

        Log::info('Facebook API Response:', $lead_data);

        // ✅ Handle Facebook API Errors
        if (isset($lead_data['error'])) {
            Log::error('Facebook API Error:', $lead_data['error']);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve lead data',
                'error'   => $lead_data['error']
            ], 400);
        }

        // ✅ Extract Lead Details
        $full_name = $email = $phone = 'N/A';
        if (isset($lead_data['field_data'])) {
            foreach ($lead_data['field_data'] as $field) {
                if ($field['name'] == "full_name") {
                    $full_name = $field['values'][0];
                } elseif ($field['name'] == "email") {
                    $email = $field['values'][0];
                } elseif ($field['name'] == "phone_number") {
                    $phone = $field['values'][0];
                }
            }
        }

        // ✅ Store Lead Data in Database
        try {
            $customer = Customer::create([
                'full_name'  => $full_name,
                'email'      => $email,
                'contact_no' => $phone,
                'lead_id'    => $leadgen_id,
                'source'     => 'Facebook',
                'status'     => '2'
            ]);

            Log::info('New lead stored:', ['customer_id' => $customer->id]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Lead stored successfully',
                'data'    => $customer
            ], 201);
        } catch (\Exception $e) {
            Log::error('Database Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to store lead data'
            ], 500);
        }
    }

}
