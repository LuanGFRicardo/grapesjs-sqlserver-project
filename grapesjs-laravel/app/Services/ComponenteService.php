<?php

namespace App\Services;

use App\Models\Componente;

class ComponenteService
{
    public function listarAtivos()
    {
        return Componente::whereNull(Componente::COL_EXCLUSAO)->get();
    }
}