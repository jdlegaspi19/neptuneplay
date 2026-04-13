<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class TransactionCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userCode' => 'required|string',
            'vendorCode' => 'required|string',
            'gameCode' => 'required|string',
            'historyId' => 'required|integer',
            'roundId' => 'required|string',
            'gameType' => 'required|integer',
            'transactionCode' => 'required|string',
            'isFinished' => 'required|boolean',
            'isCanceled' => 'required|boolean',
            'amount' => 'required|numeric',
            'detail' => 'sometimes|nullable|string',
            'createdAt' => 'sometimes|nullable|string',
        ];
    }
}
