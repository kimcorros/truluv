<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'gender' => ['nullable', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:2000'],
        ];
    }
}
