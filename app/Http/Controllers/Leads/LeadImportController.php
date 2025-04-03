<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;

class LeadImportController extends Controller
{
    public function showImportForm()
    {
        return view('leads.import'); // Create this Blade view
    }

    

    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        
        Log::info('Processing CSV file', [
            'name' => $file->getClientOriginalName(),
            'path' => $file->getRealPath()
        ]);

        try {
            $data = Excel::toArray([], $file);
            Log::info('CSV file content:', $data);

            // If CSV loads fine, then issue is in CustomerImport.php
             Excel::import(new CustomersImport, $file); // Use the correct class name

            return back()->with('success', 'Customers imported successfully!');
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

}
