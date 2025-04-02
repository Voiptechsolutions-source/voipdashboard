<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ConvertLead extends Model
{
    use HasFactory;

    protected $table = 'convert_leads';

    protected $fillable = [
        'lead_id',
        'converted_at',
        'is_active',
        'is_delete'
    ];

    protected $casts = [
        'converted_at' => 'datetime',
        'is_active' => 'boolean',
        'is_delete' => 'boolean',
    ];
}
