<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class LaunchUrlRequest extends FormRequest
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
            'language' => 'required|string',
            'lobbyUrl' => 'sometimes|nullable|string|url',
            'theme' => 'sometimes|nullable|integer',
        ];
    }
}
