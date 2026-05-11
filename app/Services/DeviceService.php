<?php

namespace App\Services;

use App\Models\Device;
use App\Models\ActivityLog;

class DeviceService
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Device::with('assignedUser')->get();
    }

    public function assign(int $deviceId, int $userId, int $authUserId): Device
    {
        $device = Device::findOrFail($deviceId);

        $device->update([
            'assigned_to' => $userId,
            'status'      => 'assigned',
            'assigned_at' => now(),
        ]);

        ActivityLog::create([
            'user_id'     => $authUserId,
            'action'      => 'device.assigned',
            'entity_type' => 'Device',
            'entity_id'   => $deviceId,
            'metadata'    => json_encode(['assigned_to' => $userId]),
            'ip_address'  => request()->ip(),
        ]);

        return $device;
    }
}
