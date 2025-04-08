<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Lead;

class FacebookWebhookController extends Controller
{
    public function handleFacebookWebhook(Request $request)
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
        Log::info('Facebook Webhook Payload:', $payload);

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
            $customer = Lead::create([
                'full_name'  => $full_name,
                'email'      => $email,
                'contact_no' => $phone,
                'lead_id'    => $leadgen_id,
                'source'     => 'Facebook',
                'status'     => 'Pending'
            ]);

            Log::info('New Facebook lead stored:', ['customer_id' => $customer->id]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Lead stored successfully',
                'data'    => $customer
            ], 201);
        } catch (\Exception $e) {
            Log::error('Facebook Webhook Database Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to store lead data'
            ], 500);
        }
    }
}
