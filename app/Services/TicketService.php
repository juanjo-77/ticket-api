<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function getAll(array $filters = [])
    {
        $query = Ticket::with(['creator', 'assignee', 'device']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function create(array $data): Ticket
    {
        $ticket = Ticket::create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'type'        => $data['type'],
            'priority'    => $data['priority'] ?? 'medium',
            'status'      => 'open',
            'created_by'  => Auth::id(),
            'device_id'   => $data['device_id'] ?? null,
        ]);

        $this->log('ticket.created', $ticket);

        return $ticket;
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);

        if (isset($data['status']) && $data['status'] === 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        }

        $this->log('ticket.updated', $ticket);

        return $ticket;
    }

    public function delete(Ticket $ticket): void
    {
        $this->log('ticket.deleted', $ticket);
        $ticket->delete();
    }

    public function assign(Ticket $ticket, int $userId): Ticket
    {
        $ticket->update(['assigned_to' => $userId, 'status' => 'in_progress']);
        $this->log('ticket.assigned', $ticket);
        return $ticket;
    }

    private function log(string $action, Ticket $ticket): void
{
    ActivityLog::create([
        'user_id'     => Auth::id(),
        'action'      => $action,
        'entity_type' => 'Ticket',
        'entity_id'   => $ticket->id,
        'ip_address'  => request()->ip(),
        'metadata'    => json_encode(['title' => $ticket->title, 'status' => $ticket->status]),
    ]);
}
}
