<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\AttendanceLogs;
use App\Models\DailyAttendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeviceDataController extends Controller
{
    public function receivePush(Request $request)
    {
        // dd($request);

        $payload = $request->all();
        $userId = $request->input('user_id') ?? $request->input('userid') ?? $request->input('pin');
        $ts = $request->input('timestamp') ?? $request->input('time') ?? now()->toDateTimeString();
        $status = $request->input('status') ?? $request->input('type') ?? 'checkin';
        $deviceSn = $request->input('device_sn') ?? $request->input('dev_sn') ?? $request->ip();

        if (!$userId) {
            return response()->json(['message' => 'bad request: missing user_id'], 400);
        }

        $device = Device::firstOrCreate(
            ['device_sn' => $deviceSn],
            ['name' => 'Unknown - ' . $deviceSn, 'status' => 'online']
        );

        $log = AttendanceLogs::create([
            'user_id' => (string)$userId,
            'timestamp' => Carbon::parse($ts),
            'status' => $status,
            'device_id' => $device->id,
            'raw_data' => $payload,
        ]);

        DB::transaction(function () use ($userId, $ts, $status, $device) {
            $date = Carbon::parse($ts)->toDateString();

            $daily = DailyAttendance::where('user_id', $userId)->where('date', $date)->lockForUpdate()->first();


            if (!$daily) {
                DailyAttendance::create([
                    'user_id' => $userId,
                    'date' => $date,
                    'first_check_in' => $status === 'checkin' ? Carbon::parse($ts) : null,
                    'last_check_out' => $status === 'checkout' ? Carbon::parse($ts) : null,
                    'device_id_in' => $status === 'checkin' ? $device->id : null,
                    'device_id_out' => $status === 'checkout' ? $device->id : null,
                ]);
            } else {
                $changed = false;
                $incoming = Carbon::parse($ts);


                if ($status === 'checkin') {
                    if (!$daily->first_check_in || $incoming->lt(Carbon::parse($daily->first_check_in))) {
                        $daily->first_check_in = $incoming;
                        $daily->device_id_in = $device->id;
                        $changed = true;
                    }
                }


                if ($status === 'checkout') {
                    if (!$daily->last_check_out || $incoming->gt(Carbon::parse($daily->last_check_out))) {
                        $daily->last_check_out = $incoming;
                        $daily->device_id_out = $device->id;
                        $changed = true;
                    }
                }


                if ($changed) $daily->save();
            }
        });


        return response()->json(['status' => 'ok']);
    }
}
