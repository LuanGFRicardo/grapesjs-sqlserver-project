<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Services\HtmlManipulatorService;

class TemplateService
{
    public function abrirEditor(Request $request, $template)
    {
        $versaoId = $request->query('versao');
        $templateModel = Template::where(Template::COL_NOME, $template)->firstOrFail();
        
        $versao = $versaoId
            ? TemplateHistorico::where('id', $versaoId)
                ->where(TemplateHistorico::COL_TEMPLATE_ID, $templateModel->id)
                ->firstOrFail()
            : TemplateHistorico::where(TemplateHistorico::COL_TEMPLATE_ID, $templateModel->id)
                ->orderByDesc(TemplateHistorico::COL_CRIACAO)
                ->firstOrFail();
        
        return view('filament.pages.editor.index', compact('templateModel', 'versao'));
    }

    public function salvarTemplate(array $data): JsonResponse
    {
        $template = Template::firstOrCreate(
            [Template::COL_NOME => $data['nome']],
            [Template::COL_CRIACAO => now()]
        );

        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML => $data['html'] ?? '',
            TemplateHistorico::COL_CSS => $data['css'] ?? '',
            TemplateHistorico::COL_PROJETO => $data['projeto'] ?? '',
        ]);

        return response()->json(['success' => true]);
    }

    public function carregarUltimaVersao($title)
    {
        $template = Template::where(Template::COL_NOME, $title)->firstOrFail();
        
        $ultimoHistorico = TemplateHistorico::where(TemplateHistorico::COL_TEMPLATE_ID, $template->id)
            ->orderByDesc(TemplateHistorico::COL_CRIACAO)
            ->firstOrFail();

        return response()->json([
            'html' => $ultimoHistorico->{TemplateHistorico::COL_HTML},
            'css' => $ultimoHistorico->{TemplateHistorico::COL_CSS},
            'projeto' => $ultimoHistorico->{TemplateHistorico::COL_PROJETO} ?? '{}',
        ]);
    }
}