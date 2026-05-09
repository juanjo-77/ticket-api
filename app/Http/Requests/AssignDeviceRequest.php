<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\DeviceService;
use App\Http\Requests\AssignDeviceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(private DeviceService $deviceService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $devices = $this->deviceService->getAll($request->only(['status', 'type']));
            return response()->json($devices);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener dispositivos',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name'          => 'required|string|max:255',
                'type'          => 'required|in:pc,laptop,mobile,tablet,other',
                'serial_number' => 'required|string|unique:devices,serial_number',
            ]);

            $device = $this->deviceService->create($data);

            return response()->json([
                'message' => 'Dispositivo creado exitosamente',
                'device'  => $device,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Device $device): JsonResponse
    {
        try {
            return response()->json($device->load('assignedUser'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Device $device): JsonResponse
    {
        try {
            $data = $request->validate([
                'name'          => 'sometimes|string|max:255',
                'type'          => 'sometimes|in:pc,laptop,mobile,tablet,other',
                'serial_number' => 'sometimes|string|unique:devices,serial_number,' . $device->id,
                'status'        => 'sometimes|in:available,assigned,maintenance',
            ]);

            $device = $this->deviceService->update($device, $data);

            return response()->json([
                'message' => 'Dispositivo actualizado exitosamente',
                'device'  => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Device $device): JsonResponse
    {
        try {
            $this->deviceService->delete($device);
            return response()->json([
                'message' => 'Dispositivo eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function assign(AssignDeviceRequest $request, Device $device): JsonResponse
    {
        try {
            $device = $this->deviceService->assign($device, $request->user_id);
            return response()->json([
                'message' => 'Dispositivo asignado exitosamente',
                'device'  => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al asignar dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function unassign(Device $device): JsonResponse
    {
        try {
            $device = $this->deviceService->unassign($device);
            return response()->json([
                'message' => 'Dispositivo desasignado exitosamente',
                'device'  => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al desasignar dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
