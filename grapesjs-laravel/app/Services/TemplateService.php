<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Services\HtmlManipulatorService;

class TemplateService
{
    public function abrirEditor(Request $request, $template)
    {
        $versaoId = $request->query('versao');
        $templateModel = Template::where('nome', $template)->firstOrFail();
        $versao = $versaoId
        ? TemplateHistorico::where('id', $versaoId)->where('template_id', $templateModel->id)->firstOrFail()
            : $templateModel->historicos()->latest('data_criacao')->first();
        
        return view('editor.index', compact('templateModel', 'versao'));
    }

    public function salvarTemplate(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'projeto' => 'nullable|string',
        ]);

        $template = Template::firstOrCreate(
            ['nome' => $validated['nome']],
            ['data_cadastro' => now()]
        );

        TemplateHistorico::create([
            'template_id' => $template->id,
            'html' => $validated['html'] ?? '',
            'css' => $validated['css'] ?? '',
            'projeto' => $validated['projeto'] ?? '',
        ]);

        return response()->json(['success' => true]);
    }

    public function carregarUltimaVersao($title)
    {
        $template = Template::where('nome', $title)->firstOrFail();
        $ultimoHistorico = TemplateHistorico::where('template_id', $template->id)
            ->orderByDesc('data_criacao')
            ->firstOrFail();

        return response()->json([
            'html' => $ultimoHistorico->html,
            'css' => $ultimoHistorico->css,
            'projeto' => $ultimoHistorico->projeto ?? '{}',
        ]);
    }

    public function abrirMenu()
    {
        $templates = Template::all(['id', 'nome']);
        return view('menu.index', compact('template'));
    }
}