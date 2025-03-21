<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ConvertLead extends Model
{
    use HasFactory;

    protected $table = 'convert_leads'; // ✅ Your table name
    protected $fillable = ['lead_id', 'converted_at']; // ✅ Fields that can be mass assigned
    public $timestamps = false; // ✅ Since you are using `converted_at` manually
}
