<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ComponenteService;
use App\Models\Componente;
use App\Enums\StatusErro;

class ComponenteController extends Controller
{
    protected $service;

    public function __construct(ComponenteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $componentes = Componente::orderBy('nome')->get();
            return view('componentes.index', compact('componentes'));
        } catch (\Exception $e) {
            $this->componenteErro('Erro ao abrir gerenciador de componentes:', $e);
        }
    }
    
    public function buscarComponente($id)
    {
        try {
            return response()->json(Componente::findOrFail($id));
        } catch (\Exception $e) {
            return $this->componenteErro('Erro ao buscar componentes:', $e);
        }
    }

    public function salvarComponente(Request $request)
    {
        try {
            $validated = $this->service->validar($request->all());
            $componente = $this->service->criar($validated);
            return response()->json($componente, 201);
        } catch (\Exception $e) {
            return $this->componenteErro('Erro ao salvar componente:', $e);
        }    
    }
    
    public function atualizarComponente(Request $request, $id)
    {
        try {
            $componente = Componente::findOrFail($id);
    
            $validated = $this->service->validar($request->all(), $id);
            $this->service->atualizar($componente, $validated);
    
            return response()->json($componente);
        } catch (\Exception $e) {
            return $this->componenteErro('Erro ao atualizar componente:', $e);
        }
    }

    public function excluirComponente($id)
    {
        try {
            $componente = Componente::findOrFail($id);
            $this->service->excluir($componente);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->componenteErro('Erro ao excluir componente:', $e);
        }
    }

    public function listarComponentes() 
    {
        try {
            return $this->service->listarAtivos();
        } catch (\Exception $e) {
            return $this->componenteErro('Erro ao listar componentes:', $e);
        }
    }

    private function componenteErro(string $contexto, \Throwable $e): JsonResponse
    {
        \Log::error($contexto, [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return response()->json([
            'error' => StatusErro::INTERNO
        ], 500);
    }
}
