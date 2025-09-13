<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLogs extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'timestamp', 'status', 'device_id', 'raw_data'];


    protected $casts = [
        'raw_data' => 'array',
        'timestamp' => 'datetime',
    ];


    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
