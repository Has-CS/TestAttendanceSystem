<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyAttendance extends Model
{
    use HasFactory;

    protected $table = 'daily_attendance';

    protected $fillable = ['user_id', 'date', 'first_check_in', 'last_check_out', 'device_id_in', 'device_id_out'];

    protected $casts = [
        'date' => 'date',
        'first_check_in' => 'datetime',
        'last_check_out' => 'datetime',
    ];


    public function deviceIn()
    {
        return $this->belongsTo(Device::class, 'device_id_in');
    }


    public function deviceOut()
    {
        return $this->belongsTo(Device::class, 'device_id_out');
    }
}

