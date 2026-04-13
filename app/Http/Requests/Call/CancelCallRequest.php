<?php

namespace App\Http\Requests\Call;

use Illuminate\Foundation\Http\FormRequest;

class CancelCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendorCode' => 'required|string',
            'gameCode' => 'required|string',
            'userCode' => 'required|string',
            'callId' => 'required|integer',
        ];
    }
}
