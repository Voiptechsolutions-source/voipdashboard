<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // ✅ Import Carbon

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'customers'; // Ensure the correct table name

    protected $fillable = [
        'full_name', 'email', 'country_code', 'contact_no', 'address',
        'pincode', 'is_active', 'is_delete', 'service_name', 'number_of_users',
        'message', 'comment', 'description', 'customer_description', 'lead_id',
        'campaign_id', 'form_id', 'source', 'status', 'convertedlead',
        'created_at' // ✅ Include created_at if filtering by date
    ];

    protected $casts = [
        'raw_data' => 'array', // Ensures raw_data is treated as an array
    ];

    
}
