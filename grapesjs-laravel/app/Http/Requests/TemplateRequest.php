<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
{
    // Autoriza qualquer usuário
    public function authorize(): bool
    {
        return true;
    }

    // Regras de validação dos campos do template
    public function rules(): array
    {
        return [
            'template_id' => 'required|integer|exists:templates,id',
            'html'        => 'nullable|string',
            'css'         => 'nullable|string',
            'projeto'     => 'nullable|string',
        ];
    }
}
