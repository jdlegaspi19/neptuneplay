<?php

namespace App\Http\Requests\Betting;

use Illuminate\Foundation\Http\FormRequest;

class BettingHistoryByDateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'startDate' => 'required|string',
            'limit' => 'required|integer|min:1|max:5000',
        ];
    }
}
