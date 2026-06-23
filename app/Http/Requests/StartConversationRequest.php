<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartConversationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'recipient_id' => [
                'required',
                'integer',
                // Can't start a conversation with yourself.
                Rule::notIn([$this->user()->id]),
                Rule::exists('users', 'id'),
            ],
        ];
    }
}
