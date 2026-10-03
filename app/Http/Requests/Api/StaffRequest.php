<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('password') === '') {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }
    }

    public function rules(): array
    {
        $staffId = $this->route('staff')?->id;
        $creating = $this->isMethod('post');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($staffId)],
            'password' => [$creating ? 'required' : 'nullable', 'confirmed', Password::min(8)],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
