<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Template;
use App\Models\TemplateHistorico;

class MenuService {
    public function criarTemplate(string $nome): array
    {
        $nome = trim($nome);

        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome inválido');
        }
    
        if (Template::where('nome', $nome)->exists()) {
            throw new \RuntimeException("Template '{$nome}' já existe");
        }

        $template = Template::create([
            Template::COL_NOME => $nome,
            Template::COL_CRIACAO => now(),
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
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML => $htmlPadrao,
            TemplateHistorico::COL_PROJETO => $gjsJson,
        ]);

        return ['success' => true, 'nome' => $nome];  
    }

    public function historicoTemplate(string $nome)
    {
        $template = Template::where(Template::COL_NOME, $nome)->firstOrFail();

        return TemplateHistorico::where(TemplateHistorico::COL_TEMPLATE_ID, $template->id)
            ->orderByDesc(TemplateHistorico::COL_CRIACAO)
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

    public function listarTemplates(): Collection
    {
        return Template::select('id', Template::COL_NOME)->get();
    }
}