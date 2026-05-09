<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $tickets = $this->ticketService->getAll($request->only([
                'status', 'priority', 'type'
            ]));

            return response()->json($tickets);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener tickets',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            $ticket = $this->ticketService->create($request->validated());

            return response()->json([
                'message' => 'Ticket creado exitosamente',
                'ticket'  => $ticket,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Ticket $ticket): JsonResponse
    {
        try {
            return response()->json(
                $ticket->load(['creator', 'assignee', 'device'])
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->update($ticket, $request->validated());

            return response()->json([
                'message' => 'Ticket actualizado exitosamente',
                'ticket'  => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        try {
            $this->ticketService->delete($ticket);

            return response()->json([
                'message' => 'Ticket eliminado exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function assign(Request $request, Ticket $ticket): JsonResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $ticket = $this->ticketService->assign($ticket, $request->user_id);

            return response()->json([
                'message' => 'Ticket asignado exitosamente',
                'ticket'  => $ticket,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al asignar ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
