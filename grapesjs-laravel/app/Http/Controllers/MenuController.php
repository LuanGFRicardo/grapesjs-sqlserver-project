<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Enums\StatusErro;

class MenuController extends Controller
{
    protected $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $templates = \App\Models\Template::all(['id', 'nome']);
            return view('menu.index', compact('templates'));
        } catch (\Exception $e) {
            return $this->menuErro('Erro ao abrir menu:', $e);
        }   
    }

    public function criarTemplate(Request $request)
    {
        try {
            $resposta = $this->service->criarTemplate($request->input('nome'));
            return response()->json($resposta);
        } catch (\Exception $e) {
            return $this->menuErro('Erro ao criar template:', $e);
        }   
    }

    public function historicoTemplate($nome)
    {
        try {
            $versoes = $this->service->historicoTemplate($nome);
            return response()->json($versoes);
        } catch(\Exception $e) {
            return $this->menuErro('Erro ao buscar histórico:', $e);
        }
    }

    public function buscarVersao($id) 
    {
        try {
            $versao = $this->service->buscarVersao($id);
            return response()->json($versao);
        } catch (\Exception $e) {
            return $this->menuErro('Erro ao buscar versão:', $e);
        }
    }

    private function menuErro(string $contexto, \Throwable $e): JsonResponse
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