<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignDeviceRequest;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function __construct(private DeviceService $deviceService) {}

    public function index(): JsonResponse
    {
        try {
            $devices = $this->deviceService->getAll();

            return response()->json([
                'success' => true,
                'data'    => $devices,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener dispositivos',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function assign(AssignDeviceRequest $request): JsonResponse
    {
        try {
            $device = $this->deviceService->assign(
                $request->device_id,
                $request->user_id,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo asignado correctamente',
                'data'    => $device,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar dispositivo',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
