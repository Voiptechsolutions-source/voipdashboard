<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // ✅ Import Carbon

class LeadHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'leads_history'; // Updated table name

    protected $fillable = [
        'status', 'comment', 'lead_id', 'added_by','is_deleted','created_at', 'updated_at' // ✅ Include created_at if filtering by date
    ];


    // Relationship: Each Support entry belongs to a Lead
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
