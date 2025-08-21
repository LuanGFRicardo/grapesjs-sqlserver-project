<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Models\Noticia;
use App\Services\HtmlManipulatorService;

class TemplateService
{
    public function __construct(
        private  GrapesJSConversorService $conversor,
    ) {}

    // Cria template convertendo HTML para GrapesJS
    public function criarTemplate(string $nome, string $conteudoHtml): array
    {
        $nomeNormalizado = $this->normalizarNomeTemplate($nome);
        $htmlContainer = '<div class="container">' . $conteudoHtml . '</div>';
        $components = $this->conversor->htmlToGrapesComponents($conteudoHtml);

        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages' => [[
                'name' => $nomeNormalizado,
                'styles' => [],
                'frames' => [[
                    'component' => [
                        'tagName' => 'div',
                        'components' => $components,
                    ],
                ]],
            ]],
        ], JSON_UNESCAPED_UNICODE);

        // Salva versão GrapesJS e conteúdo textual
        $template = $this->criarVersaoTemplate($nomeNormalizado, $htmlContainer, '', $gjsJson);

        return [
            'success' => true,
            'id' => $template->id,
            'nome' => $nomeNormalizado
        ];
    }

    // Cria versão histórica do template
    public function criarVersaoTemplate(string $nome, string $html, string $css, string $projeto): Template
    {
        $nome = trim($nome);
        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome inválido');
        }

        $template = Template::create([
            Template::COL_NOME => $nome,
            Template::COL_CRIACAO => now(),
        ]);

        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML        => $html,
            TemplateHistorico::COL_CSS         => $css ?? '',
            TemplateHistorico::COL_PROJETO     => $projeto ?? '',
        ]);

        return $template;
    }

    // Atualiza o nome do template
    public function atualizarNomeTemplate(int $templateId, string $novoNome): array
    {
        $novoNomeNormalizado = $this->normalizarNomeTemplate($novoNome);

        // Busca o template pelo ID
        $template = Template::find($templateId);

        if (!$template) {
            return [
                'success' => false,
                'error' => 'Template não encontrado com o ID: ' . $templateId
            ];
        }

        // Atualiza apenas o nome
        $template->nome = $novoNomeNormalizado;
        $template->save();

        return [
            'success' => true,
            'nome' => $novoNomeNormalizado
        ];
    }

    // Normaliza nome do template para slug sem acentos
    public function normalizarNomeTemplate(string $nome): string
    {
        $nome = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
        $nome = preg_replace('/[^A-Za-z0-9\s\-]/', '', $nome);
        $nome = preg_replace('/[\s\-]+/', '-', $nome);
        return strtolower(trim($nome, '-'));
    }
}
