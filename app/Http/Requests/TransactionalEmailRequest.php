<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionalEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'         => 'required|in:text,html',
            'recipients'   => 'required|array',
            'recipients.*' => 'email',
            'subject'      => 'required',
            'content'      => 'required|array',
        ];
    }
}
