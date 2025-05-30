<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaixarTemplateRequest extends FormRequest
{
    // Permite autorização para qualquer usuário
    public function authorize(): bool
    {
        return true;
    }

    // Validação para o campo template_id
    public function rules(): array
    {
        return [
            'template_id' => 'required|integer|exists:templates,id',
        ];
    }
}
