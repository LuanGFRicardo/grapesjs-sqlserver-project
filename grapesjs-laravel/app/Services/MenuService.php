<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateHistorico;

class MenuService {
    public function criarTemplate(string $nome): array
    {
        $nome = trim($nome);

        if (!$nome) {
            throw new \InvalidArgumentException('Nome inválido');
        }

        if (Template::where('nome', $nome)->exists()) {
            throw new \RuntimeException('Template já existe');
        }

        $template = Template::create([
            'nome' => $nome,
            'data_cadastro' => now(),
        ]);

        $htmlPadrao = '<div class="container"><h1>Novo Template Criado</h1><p>Comece aqui...</p></div>';
        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages' => [[
                'name' => $nome,
                'styles' => [],
                'frames' => [[
                    'component' => [
                        'tagName' => 'div',
                        'components' => [['type' => 'text', 'content' => 'Novo template']]
                    ]
                ]]
            ]]
        ], JSON_UNESCAPED_UNICODE);

        TemplateHistorico::create([
            'template_id' => $template->id,
            'html' => $htmlPadrao,
            'projeto' => $gjsJson,
        ]);

        return ['success' => true, 'nome' => $nome];  
    }

    public function historicoTemplate(string $nome)
    {
        $template = Template::where('nome', $nome)->firstOrFail();

        return TemplateHistorico::where('template_id', $template->id)
            ->orderByDesc('data_criacao')
            ->get();
    }

    public function buscarVersao(int $id): array
    {
        $versao = TemplateHistorico::findOrFail($id);
        $versaoJson = [
            [
                '' => $versao->id,
                'html' => $versao->html,
                'projeto' => $versao->projeto,
                'data_criacao' => $versao->data_criacao,
                'data_modificacao' => $versao->data_modificacao,
            ]
        ];

        return $versaoJson;
    }
}