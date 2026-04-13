<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class SetUserRtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendorCode' => 'required|string',
            'userCode' => 'required|string',
            'rtp' => 'required|integer|min:30|max:99',
        ];
    }
}
