<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componente;
use App\Models\Template;
use App\Models\TemplateHistorico;

class MenuController extends Controller
{
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

            // Conteúdo HTML inicial
            $htmlPadrao = '<div class="container"><h1>Novo Template Criado</h1><p>Comece aqui...</p></div>';

            // Estrutura inicial
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

            // Registra versão inicial no histórico
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

            // Recupera todas as versões do template
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