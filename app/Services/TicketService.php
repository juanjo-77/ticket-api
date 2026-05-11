<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\ActivityLog;

class TicketService
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::with(['creator', 'assignee', 'device'])->get();
    }

    public function getById(int $id): Ticket
    {
        return Ticket::with(['creator', 'assignee', 'device'])->findOrFail($id);
    }

    public function create(array $data, int $userId): Ticket
    {
        $data['created_by'] = $userId;
        $ticket = Ticket::create($data);

        $this->log($userId, 'ticket.created', $ticket->id, $data);

        return $ticket;
    }

    public function update(int $id, array $data, int $userId): Ticket
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);

        if (isset($data['status']) && $data['status'] === 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        }

        $this->log($userId, 'ticket.updated', $ticket->id, $data);

        return $ticket;
    }

    public function delete(int $id, int $userId): void
    {
        $ticket = Ticket::findOrFail($id);
        $this->log($userId, 'ticket.deleted', $ticket->id, []);
        $ticket->delete();
    }

    private function log(int $userId, string $action, int $entityId, array $metadata): void
{
    ActivityLog::create([
        'user_id'     => $userId,
        'action'      => $action,
        'entity_type' => 'Ticket',
        'entity_id'   => $entityId,
        'metadata'    => json_encode($metadata),
        'ip_address'  => request()->ip(),
    ]);
}
}
