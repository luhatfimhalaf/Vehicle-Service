<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'plate_number',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];
}
