<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComponenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('componentes', 'nome')->ignore($id)
            ],
            'categoria' => 'nullable|string|max:255',
            'html' => 'required|string',
            'css' => 'nullable|string',
            'icone' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^(fa[a-z\- ]+)?$/i',
            ],
        ];
    }
}
