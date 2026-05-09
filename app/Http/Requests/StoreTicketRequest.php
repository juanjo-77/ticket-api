<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:incident,assignment,maintenance',
            'priority'    => 'sometimes|in:low,medium,high,critical',
            'device_id'   => 'sometimes|nullable|exists:devices,id',
        ];
    }
}
