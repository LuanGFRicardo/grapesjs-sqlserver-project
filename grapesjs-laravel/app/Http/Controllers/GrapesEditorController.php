<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TemplateService;
use App\Services\FileService;
use App\Enums\StatusErro;
use App\Http\Requests\TemplateRequest;
use App\Http\Requests\BaixarTemplateRequest;
use App\Traits\HandlesExceptions;

class GrapesEditorController extends Controller
{
    use HandlesExceptions;

    public function __construct(
        private TemplateService $templateService,
        private FileService $fileService
    ) {}

    // Abre o editor para o template
    public function index(Request $request, $template)
    {
        try {
            return $this->templateService->abrirEditor($request, $template);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao abrir editor:', $e);
        }        
    }    

    // Salva o template validado
    public function salvarTemplate(TemplateRequest $request)
    {
        try {
            $template = $request->validated();
            return $this->templateService->salvarTemplate($template);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao salvar template:', $e);
        }        
    }

    // Carrega a última versão do template
    public function carregarUltimaVersao($title)
    {
        try {
            return $this->templateService->carregarUltimaVersao($title);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao carregar última versão do template:', $e);
        }
    }

    // Faz upload de imagem
    public function uploadImagem(Request $request) 
    {
        try {
            return $this->fileService->uploadImagem($request);
        } catch (\Exception $e) {
            return $this->handleException('Erro fazer upload de imagem:', $e);
        }
    }

    // Baixa o template em arquivo ZIP
    public function baixarTemplate(BaixarTemplateRequest $request)
    {
        try {
            $path = $this->fileService->gerarTemplateZip($request->validated()['template_id']);
        
            if (!$path || !file_exists($path)) {
                return response()->json(['error' => 'Arquivo ZIP não encontrado.'], 404);
            }
        
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao gerar arquivo ZIP:', $e);
        }    
    }
}
