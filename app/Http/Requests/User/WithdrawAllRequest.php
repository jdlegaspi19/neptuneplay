<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawAllRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userCode' => 'required|string',
            'vendorCode' => 'sometimes|nullable|string',
        ];
    }
}
