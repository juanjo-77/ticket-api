<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type'        => 'sometimes|in:incident,assignment,maintenance',
            'priority'    => 'sometimes|in:low,medium,high,critical',
            'status'      => 'sometimes|in:open,in_progress,resolved,closed',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'device_id'   => 'sometimes|nullable|exists:devices,id',
        ];
    }
}
