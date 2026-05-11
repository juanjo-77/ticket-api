<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'required|exists:devices,id',
            'user_id'   => 'required|exists:users,id',
        ];
    }
}
