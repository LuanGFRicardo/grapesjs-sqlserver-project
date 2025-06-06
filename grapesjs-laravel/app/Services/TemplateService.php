<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Services\HtmlManipulatorService;

class TemplateService
{
    // Abre o editor com a versão solicitada ou a mais recente
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

        return view('editor.index', compact('templateModel', 'versao'));
    }

    // Salva nova versão do template e atualiza notícia associada
    public function salvarTemplate(array $data): JsonResponse
    {
        $template = $this->salvarVersaoTemplate(
            $data['nome'],
            $data['html'] ?? '',
            $data['css'] ?? '',
            $data['projeto'] ?? ''
        );

        return response()->json(['success' => true]);
    }

    // Cria ou atualiza versão histórica do template
    public function salvarVersaoTemplate(string $nome, string $html, string $css = null, string $projeto = null): Template
    {
        $nome = trim($nome);
        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome inválido');
        }

        $template = Template::firstOrCreate(
            [Template::COL_NOME => $nome],
            [Template::COL_CRIACAO => now()]
        );

        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML        => $html,
            TemplateHistorico::COL_CSS         => $css ?? '',
            TemplateHistorico::COL_PROJETO     => $projeto ?? '',
        ]);

        return $template;
    }

    // Atualiza ou cria template convertendo HTML para GrapesJS
    public function atualizarOuCriarTemplate(string $nome, string $conteudoHtml): array
    {
        $nomeNormalizado = $this->normalizarNomeTemplate($nome);

        $htmlPadrao = '<div class="container">' . $conteudoHtml . '</div>';

        $components = $this->htmlToGrapesComponents($conteudoHtml);

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

        $template = $this->salvarVersaoTemplate($nomeNormalizado, $htmlPadrao, '', $gjsJson);

        return [
            'success' => true,
            'nome' => $nomeNormalizado
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

    // Converte HTML em componentes GrapesJS
    public function htmlToGrapesComponents(string $conteudoHtml): array
    {
        $conteudoHtmlUtf8 = mb_convert_encoding($conteudoHtml, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        @$dom->loadHTML('<div>' . $conteudoHtmlUtf8 . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        $body = $dom->getElementsByTagName('div')->item(0);

        $components = [];

        foreach ($body->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $components[] = $this->domNodeToGrapesComponent($node);
            } elseif ($node->nodeType === XML_TEXT_NODE) {
                $text = trim($node->textContent);
                if ($text !== '') {
                    $components[] = $text;
                }
            }
        }

        return $components;
    }

    // Converte nó do DOM em componente GrapesJS
    public function domNodeToGrapesComponent(\DOMNode $node): array
    {
        $component = [
            'tagName' => $node->nodeName,
            'type' => $this->mapTagToType($node->nodeName),
        ];

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $component['attributes'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $text = trim($child->textContent);
                    if ($text !== '') {
                        $component['components'][] = $text;
                    }
                } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                    $component['components'][] = $this->domNodeToGrapesComponent($child);
                }
            }
        }

        return $component;
    }

    // Mapeia tags HTML para tipos GrapesJS
    private function mapTagToType(string $tag): string
    {
        $map = [
            'p' => 'text',
            'img' => 'image',
            'a' => 'link',
            'figure' => 'figure',
            'ul' => 'list',
            'li' => 'list-item',
        ];

        return $map[$tag] ?? $tag;
    }

    // Carrega última versão histórica do template
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
