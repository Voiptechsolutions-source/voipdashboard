<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    protected $fillable = ['name', 'emails'];
    protected $casts = ['emails' => 'array'];
}