<?php

namespace App\Services;

use App\Models\Device;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DeviceService
{
    public function getAll(array $filters = [])
    {
        $query = Device::with('assignedUser');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function create(array $data): Device
    {
        $device = Device::create($data);
        $this->log('device.created', $device);
        return $device;
    }

    public function update(Device $device, array $data): Device
    {
        $device->update($data);
        $this->log('device.updated', $device);
        return $device;
    }

    public function delete(Device $device): void
    {
        $this->log('device.deleted', $device);
        $device->delete();
    }

    public function assign(Device $device, int $userId): Device
    {
        $device->update([
            'assigned_to' => $userId,
            'status'      => 'assigned',
            'assigned_at' => now(),
        ]);

        $this->log('device.assigned', $device);
        return $device;
    }

    public function unassign(Device $device): Device
    {
        $device->update([
            'assigned_to' => null,
            'status'      => 'available',
            'assigned_at' => null,
        ]);

        $this->log('device.unassigned', $device);
        return $device;
    }

    private function log(string $action, Device $device): void
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'entity_type' => 'Device',
            'entity_id'   => $device->id,
            'ip_address'  => request()->ip(),
            'metadata'    => json_encode(['name' => $device->name, 'status' => $device->status]),
        ]);
    }
}
