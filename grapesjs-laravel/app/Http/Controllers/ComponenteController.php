<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ComponenteService;
use App\Enums\StatusErro;
use App\Traits\HandlesExceptions;

class ComponenteController extends Controller
{
    use HandlesExceptions;

    protected $service;

    public function __construct(ComponenteService $service)
    {
        $this->service = $service;
    }

    // Retorna componentes ativos ou trata erro
    public function listarComponentes() 
    {
        try {
            return $this->service->listarAtivos();
        } catch (\Exception $e) {
            return $this->handleException('Erro ao listar componentes:', $e);
        }
    }
}
