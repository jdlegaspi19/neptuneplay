<?php

namespace App\Http\Requests\Call;

use Illuminate\Foundation\Http\FormRequest;

class SendCallRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'type' => 'required|integer',
        ];
    }
}
