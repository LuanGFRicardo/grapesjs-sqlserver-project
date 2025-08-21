<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Models\Noticia;
use App\Services\HtmlManipulatorService;

class GrapesJSEditorService
{
    // Abre o editor com a versão solicitada ou a mais recente
    public function abrirEditor(Request $request, $templateId)
    {
        $versaoId = $request->query('versao');

        if (!$versaoId) {
            abort(400, 'Versão obrigatória.');
        }

        $versao = TemplateHistorico::with('template')
            ->where('id', $versaoId)
            ->firstOrFail();

        // Verifica se a versão pertence ao template
        if ($versao->template?->id != $templateId) {
            abort(404, 'Template e versão não correspondem.');
        }

        $templateModel = $versao->template;

        return view('editor.index', [
            'templateModel' => $templateModel,
            'versao' => $versao,
        ]);
    }

    // Salva nova versão do template
    public function salvarTemplate(array $data): JsonResponse
    {
        $templateId = $data['template_id'] ?? null;
        $html = $data['html'] ?? '';
        $css = $data['css'] ?? '';
        $projeto = $data['projeto'] ?? '';

        if (empty($templateId) || !is_numeric($templateId)) {
            throw new \InvalidArgumentException('Template ID inválido');
        }

        $template = Template::findOrFail($templateId);

        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML        => $html,
            TemplateHistorico::COL_CSS         => $css,
            TemplateHistorico::COL_PROJETO     => $projeto,
        ]);

        return response()->json(['success' => true]);
    }

    // Carrega última versão do template
    public function carregarUltimaVersao($templateId)
    {
        $template = Template::findOrFail($templateId);

        $ultimoHistorico = $template->historicos()
            ->orderByDesc(TemplateHistorico::COL_CRIACAO)
            ->firstOrFail();

        return response()->json([
            'html' => $ultimoHistorico->{TemplateHistorico::COL_HTML},
            'css' => $ultimoHistorico->{TemplateHistorico::COL_CSS},
            'projeto' => $ultimoHistorico->{TemplateHistorico::COL_PROJETO} ?? '{}',
        ]);
    }
}
