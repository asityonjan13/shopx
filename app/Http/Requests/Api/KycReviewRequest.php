<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class KycReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,approved,rejected'],
            'rejected_reason' => ['nullable', 'required_if:status,rejected', 'string', 'max:1000'],
        ];
    }
}
