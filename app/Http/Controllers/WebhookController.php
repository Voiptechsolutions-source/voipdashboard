<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer; // Ensure you import the Customer model

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
}
