<?php

namespace App\Http\Requests\Call;

use Illuminate\Foundation\Http\FormRequest;

class CallHistoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'required|integer|min:1|max:100',
        ];
    }
}
