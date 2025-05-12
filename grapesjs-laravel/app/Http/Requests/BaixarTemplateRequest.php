<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaixarTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => 'required|integer|exists:templates,id',
        ];
    }
}
