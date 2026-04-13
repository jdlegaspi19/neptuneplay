<?php

namespace App\Http\Requests\Betting;

use Illuminate\Foundation\Http\FormRequest;

class BettingHistoryByIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }
}
