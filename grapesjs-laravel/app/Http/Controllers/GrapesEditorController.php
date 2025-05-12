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

    public function index(Request $request, $template)
    {
        try {
            return $this->templateService->abrirEditor($request, $template);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao abrir editor:', $e);
        }        
    }    

    public function salvarTemplate(TemplateRequest $request)
    {
        try {
            $template = $request->validated();
            return $this->templateService->salvarTemplate($template);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao salvar template:', $e);
        }        
    }

    public function carregarUltimaVersao($title)
    {
        try {
            return $this->templateService->carregarUltimaVersao($title);
        } catch (\Exception $e) {
            return $this->handleException('Erro ao carregar última versão do template:', $e);
        }
    }

    public function uploadImagem(Request $request) 
    {
        try {
            return $this->fileService->uploadImage($request);
        } catch (\Exception $e) {
            return $this->handleException('Erro fazer upload de imagem:', $e);
        }
    }

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

    public function template()
    {
        return view('template');
    }
}
