<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ComponenteService;
use App\Enums\StatusErro;
use App\Http\Requests\ComponenteRequest;
use App\Traits\HandlesExceptions;

class ComponenteController extends Controller
{
    use HandlesExceptions;

    protected $service;

    public function __construct(ComponenteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $componentes = $this->service->listarTodos();
            
            return view('filament.pages.componentes.index', compact('componentes'));
        } catch (\Exception $e) {
            $this->handleException('Erro ao abrir gerenciador de componentes:', $e);
        }
    }
    
    public function buscarComponente($id)
    {
        try {
            $componente = $this->service->buscarComponente($id);

            return response()->json($componente);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao buscar componentes:', $e);
        }
    }

    public function salvarComponente(ComponenteRequest $request)
    {
        try {
            $validated = $request->validated();
            $componente = $this->service->criar($validated);

            return response()->json($componente, 201);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao salvar componente:', $e);
        }    
    }
    
    public function atualizarComponente(ComponenteRequest $request, $id)
    {
        try {
            $componente = $this->service->buscarComponente($id);
            $validated = $request->validated();
            $this->service->atualizar($componente, $validated);

            return response()->json($componente);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao atualizar componente:', $e);
        }
    }

    public function excluirComponente($id)
    {
        try {
            $componente = $this->service->buscarComponente($id);
            $this->service->excluir($componente);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao excluir componente:', $e);
        }
    }

    public function listarComponentes() 
    {
        try {
            return $this->service->listarAtivos();
        } catch (\Exception $e) {
            return $this->handleException('Erro ao listar componentes:', $e);
        }
    }
}
