<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Http\Requests\CriarTemplateRequest;
use App\Traits\HandlesExceptions;

class MenuController extends Controller
{
    use HandlesExceptions;

    protected $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $templates = $this->service->listarTemplates();
            return view('filament.pages.menu.index', compact('templates'));
        } catch (\Exception $e) {
            return $this->handleException('Erro ao abrir menu:', $e);
        }   
    }

    // TODO Template já existe
    public function criarTemplate(CriarTemplateRequest $request)
    {
        try {
            $resposta = $this->service->criarTemplate($request->validated()['nome']);
            return response()->json($resposta);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao criar template:', $e);
        }   
    }

    public function historicoTemplate($nome)
    {
        try {
            $versoes = $this->service->historicoTemplate($nome);
            return response()->json($versoes);
        } catch(\Exception $e) {
            return $this->handleException('Erro ao buscar histórico:', $e);
        }
    }

    public function buscarVersao($id) 
    {
        try {
            $versao = $this->service->buscarVersao($id);
            return response()->json($versao);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao buscar versão:', $e);
        }
    }
}