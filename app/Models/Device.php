<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    protected $fillable = ['device_sn', 'name', 'department', 'ip_address', 'status'];


    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLogs::class);
    }
}
