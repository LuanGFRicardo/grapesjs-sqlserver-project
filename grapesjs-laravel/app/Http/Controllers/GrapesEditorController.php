<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TemplateService;
use App\Services\FileService;
use App\Enums\StatusErro;

class GrapesEditorController extends Controller
{
    public function __construct(
        private TemplateService $templateService,
        private FileService $fileService
    ) {}

    public function index(Request $request, $template)
    {
        try {
            return $this->templateService->abrirEditor($request, $template);
        } catch (\Exception $e) {
            $this->editorErro('Erro ao abrir editor:', $e);
        }        
    }    

    public function salvarTemplate(Request $request)
    {
        try {
            return $this->templateService->salvarTemplate($request);
        } catch (\Exception $e) {
            $this->editorErro('Erro ao salvar template:', $e);
        }        
    }

    public function carregar($title)
    {
        try {
            return $this->templateService->carregarUltimaVersao($title);
        } catch (\Exception $e) {
            $this->editorErro('Erro ao carregar última versão do template:', $e);
        }
    }

    public function menu()
    {
        try {
            return $this->templateService->abrirMenu();
        } catch (\Exception $e) {
            $this->editorErro('Erro ao acessar menu:', $e);
        }
    }

    public function uploadImagem(Request $request) 
    {
        try {
            return $this->fileService->uploadImage($request);
        } catch (\Exception $e) {
            return $this->editorErro('Erro fazer upload de imagem:', $e);
        }
    }

    public function baixarTemplate(Request $request)
    {
        try {
            $validated = $request->validate([
                'template_id' => 'required|integer|exists:templates,id'
            ]);
        
            $path = $this->fileService->gerarTemplateZip($validated['template_id']);
        
            if (!$path || !file_exists($path)) {
                return response()->json(['error' => 'Arquivo ZIP não encontrado.'], 404);
            }
        
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return $this->editorErro('Erro ao gerar arquivo ZIP:', $e);
        }    
    }    

    public function template()
    {
        return view('template');
    }

    private function editorErro(string $contexto, \Throwable $e): JsonResponse
    {
        \Log::error($contexto, [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response->json([
            'error' => StatusErro::INTERNO
        ], 500);
    }
}
