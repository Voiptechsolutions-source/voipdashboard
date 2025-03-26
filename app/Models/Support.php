<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // ✅ Import Carbon
use App\Models\ConvertLead; // ✅ Import ConvertLead Model

class Support extends Model
{
    use HasFactory;

    protected $table = 'supports'; // ✅ Ensure table name matches the DB

    protected $fillable = [
        'lead_id', 'notes', 'revenue_per_day', 'created_at' // ✅ Include created_at if filtering by date
    ];

    // Relationship: Each Support entry belongs to a Lead
    public function lead()
    {
        return $this->belongsTo(ConvertLead::class, 'lead_id');
    }

    
}
