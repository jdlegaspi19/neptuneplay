<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class BatchTransactionCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userCode' => 'required|string',
            'transactions' => 'required|array',
            'transactions.*.vendorCode' => 'required|string',
            'transactions.*.gameCode' => 'required|string',
            'transactions.*.historyId' => 'required|integer',
            'transactions.*.roundId' => 'required|string',
            'transactions.*.gameType' => 'required|integer',
            'transactions.*.transactionCode' => 'required|string',
            'transactions.*.isFinished' => 'required|boolean',
            'transactions.*.isCanceled' => 'required|boolean',
            'transactions.*.amount' => 'required|numeric',
            'transactions.*.detail' => 'sometimes|nullable|string',
            'transactions.*.createdAt' => 'sometimes|nullable|string',
        ];
    }
}
