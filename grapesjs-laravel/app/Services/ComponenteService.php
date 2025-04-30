<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Componente;

class ComponenteService
{
    public function validar(array $data, $id = null): array
    {
        $rules = [
            'nome' => 'required|string|max:255|unique:componentes,nome' . ($id ? ',' . $id . ',id' : ''),
            'categoria' => 'nullable|string|max:255',
            'html' => 'required|string',
            'css' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function criar(array $data)
    {
        $data['data_cricao'] = now();
        return Componente::create($data);           
    }

    public function atualizar(Componente $componente, array $data): Componente
    {
        $data['data_modificacao'] = now();
        $componente->update($data);
        return $componente;
    }

    public function excluir(Componente $componente)
    {
        $componente->data_exclusao = now();
        $componente->save();
        return $componente;
    }

    public function listarAtivos()
    {
        return Componente::whereNull('data_exclusao')->get();
    }
}