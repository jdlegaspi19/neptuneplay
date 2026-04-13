<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userCode' => 'required|string',
            'balance' => 'required|numeric|min:0',
            'orderNo' => 'sometimes|nullable|string',
            'vendorCode' => 'sometimes|nullable|string',
        ];
    }
}
