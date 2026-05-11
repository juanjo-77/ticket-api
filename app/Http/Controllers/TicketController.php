<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function index(): JsonResponse
    {
        try {
            $tickets = $this->ticketService->getAll();

            return response()->json([
                'success' => true,
                'data'    => $tickets,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tickets',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $ticket = $this->ticketService->getById($id);

            return response()->json([
                'success' => true,
                'data'    => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket no encontrado',
                'error'   => $e->getMessage(),
            ], 404);
        }
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            $ticket = $this->ticketService->create(
                $request->validated(),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Ticket creado correctamente',
                'data'    => $ticket,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $ticket = $this->ticketService->update(
                $id,
                $request->only(['title', 'description', 'status', 'priority', 'assigned_to']),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Ticket actualizado correctamente',
                'data'    => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->ticketService->delete($id, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Ticket eliminado correctamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
