<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class KycStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'full_address' => ['required', 'string', 'max:500'],
            'document_type' => ['required', 'in:passport,driving_license,id_card'],
            'document_scan_copy' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
}
