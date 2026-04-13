<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class BatchRtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendorCode' => 'required|string',
            'data' => 'required|array|max:500',
            'data.*.userCode' => 'required|string',
            'data.*.rtp' => 'required|integer|min:30|max:99',
        ];
    }
}
