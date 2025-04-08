<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;

class GoogleWebhookController extends Controller
{
    public function handleGoogleWebhook(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Google Webhook received: ', $request->all());

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
            Lead::create([
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
            Log::error('Google Webhook Database Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Database Error'], 500);
        }
    }
}
