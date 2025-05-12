<?php

namespace App\Services;

use App\Models\Componente;

class ComponenteService
{
    public function criar(array $data)
    {
        $data[Componente::COL_CRIACAO] = now();
        return Componente::create($data);
    }

    public function atualizar(Componente $componente, array $data): Componente
    {
        $data[Componente::COL_MODIFICACAO] = now();
        $componente->update($data);
        return $componente;
    }

    public function excluir(Componente $componente)
    {
        $componente->data_exclusao = now();
        $componente->save();
        return $componente;
    }

    public function listarTodos() 
    {
        return Componente::orderBy(Componente::COL_NOME)->get();
    }

    public function listarAtivos()
    {
        return Componente::whereNull(Componente::COL_EXCLUSAO)->get();
    }

    public function buscarComponente($id = null) 
    {
        return Componente::findOrFail($id);
    }
}