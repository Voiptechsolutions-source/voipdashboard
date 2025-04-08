<?php
namespace App\Imports;

use App\Models\Lead;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;


class CustomersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
         return Lead::updateOrCreate(
            ['email' => $row['email']], // Unique key to check
            [
                'full_name'           => $row['full_name'],
                'country_code'        => $row['country_code'],
                'contact_no'          => $row['contact_no'],
                'address'             => $row['address'],
                'pincode'             => $row['pincode'],
                'service_name'        => $row['service_name'], 
                'number_of_users'     => $row['number_of_users'],
                'message'             => $row['message'],
                'comment'             => $row['comment'],
                'description'         => $row['description'],
                'service_type'        => $row['service_type'],
                'industry'            => $row['industry'],     
                'customer_description'=> $row['customer_description'],
                'campaign_id'         => $row['campaign_id'],
                'form_id'             => $row['form_id'],
                'source'              => $row['source'],
                'status'              => $row['status'],
            ]
        );

        
    }
}
