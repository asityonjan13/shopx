<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table = $this->is('api/admin/*') ? 'admins' : 'users';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.$table.',email,'.$this->user()->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
