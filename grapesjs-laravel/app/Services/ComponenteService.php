<?php

namespace App\Services;

use App\Models\Componente;

class ComponenteService
{
    // Retorna componentes ativos
    public function listarAtivos()
    {
        // Busca componentes sem data de exclusão
        return Componente::whereNull(Componente::COL_EXCLUSAO)->get();
    }
}
