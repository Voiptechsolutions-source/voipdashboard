<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // ✅ Import Carbon

class Lead extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'leads'; // Updated table name

    protected $fillable = [
        'full_name', 'email', 'country_code', 'contact_no', 'address', 'pincode', 'industry', 'service_type', 'service_name', 'number_of_users', 
        'message', 'comment', 'description', 'customer_description', 
        'campaign_id', 'form_id', 'source', 'status', 
        'convertedlead', 'raw_data' // ✅ Include created_at if filtering by date
    ];

    protected $casts = [
        'raw_data' => 'array', // Ensures raw_data is treated as an array
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
