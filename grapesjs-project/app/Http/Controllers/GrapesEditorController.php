<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateHistorico;

class GrapesEditorController extends Controller
{
    public function index(Request $request, $template)
    {
        $versaoId = $request->query('versao');
    
        $templateModel = Template::where('nome', $template)->firstOrFail();
    
        $versao = $versaoId
            ? TemplateHistorico::where('id', $versaoId)
                ->where('template_id', $templateModel->id)
                ->firstOrFail()
            : $templateModel->historicos()->latest('data_criacao')->first();
    
        return view('grapes-editor', [
            'template' => $templateModel,
            'versao' => $versao,
        ]);
    }    

    public function salvarTemplate(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'html' => 'nullable|string',
                'projeto' => 'nullable|string',
            ]);

            \Log::info('Dados validados:', $validated);

            // Cria ou recupera o template base (sem HTML nem projeto)
            $template = Template::firstOrCreate(
                ['nome' => $validated['nome']],
                ['data_cadastro' => now()]
            );

            // Cria nova entrada no histórico
            TemplateHistorico::create([
                'template_id' => $template->id,
                'html' => $validated['html'] ?? '',
                'projeto' => $validated['projeto'] ?? '',
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Erro de validação ao salvar template:', $ve->errors());
            return response()->json(['error' => 'Erro de validação.', 'details' => $ve->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar template:', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao salvar template.'], 500);
        }
    }

    public function carregar($title)
    {
        $template = Template::where('nome', $title)->first();

        if (!$template) {
            return response()->json(['error' => 'Template não encontrado'], 404);
        }

        $ultimoHistorico = TemplateHistorico::where('template_id', $template->id)
            ->orderByDesc('data_criacao')
            ->first();

        if (!$ultimoHistorico) {
            return response()->json(['error' => 'Nenhuma versão salva encontrada'], 404);
        }

        return response()->json([
            'html' => $ultimoHistorico->html,
            'css' => '',
            'projeto' => $ultimoHistorico->projeto ?? '{}',
        ]);
    }

    public function menu()
    {
        $templates = Template::all(['id', 'nome']);
        return view('grapes-editor-menu', compact('templates'));
    }

    public function criarTemplate(Request $request)
    {
        try {
            $nome = trim($request->input('nome'));

            if (!$nome) {
                return response()->json(['error' => 'Nome inválido'], 400);
            }

            if (Template::where('nome', $nome)->exists()) {
                return response()->json(['error' => 'Template já existe'], 400);
            }

            // Cria template base
            $template = Template::create([
                'nome' => $nome,
                'data_cadastro' => now()
            ]);

            // Define conteúdo inicial
            $htmlPadrao = '<div class="container"><h1>Novo Template Criado</h1><p>Comece aqui...</p></div>';

            $gjsJson = json_encode([
                'assets' => [],
                'styles' => [],
                'pages' => [
                    [
                        'name' => $nome,
                        'styles' => [],
                        'frames' => [[
                            'component' => [
                                'tagName' => 'div',
                                'components' => [
                                    ['type' => 'text', 'content' => 'Novo template']
                                ]
                            ]
                        ]]
                    ]
                ]
            ], JSON_UNESCAPED_UNICODE);

            // Cria versão inicial no histórico
            TemplateHistorico::create([
                'template_id' => $template->id,
                'html' => $htmlPadrao,
                'projeto' => $gjsJson,
            ]);

            return response()->json(['success' => true, 'nome' => $nome]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar template: ', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar histórico.'], 500);
        }
    }

    public function historicoTemplate($nome)
    {
        try {
            $template = Template::where('nome', $nome)->firstOrFail();

            $versoes = TemplateHistorico::where('template_id', $template->id)
                ->orderByDesc('data_criacao')
                ->get();

            return response()->json($versoes);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar histórico:', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar histórico.'], 500);
        }
    }

    public function buscarVersao($id) 
    {
        try {
            $versao = TemplateHistorico::findOrFail($id);

            return response()->json([
                '' => $versao->id,
                'html' => $versao->html,
                'projeto' => $versao->projeto,
                'data_criacao' => $versao->data_criacao,
                'data_modificacao' => $versao->data_modificacao,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar versão:', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar versão.'], 500);
        }
    }
}
