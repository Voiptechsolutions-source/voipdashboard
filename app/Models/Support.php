<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $table = 'supports';
    
    protected $fillable = ['lead_id', 'notes', 'revenue_per_day'];

    public function lead()
    {
        return $this->belongsTo(ConvertLead::class, 'lead_id');
    }
}
