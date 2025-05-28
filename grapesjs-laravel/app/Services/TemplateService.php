<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Services\HtmlManipulatorService;
use Symfony\Component\DomCrawler\Crawler;

class TemplateService
{
    /**
     * Exibe o editor de template, carregando a versão solicitada ou a mais recente.
     *
     * @param Request $request
     * @param string $template
     * @return \Illuminate\View\View
     */
    public function abrirEditor(Request $request, $template)
    {
        // Obtém ID da versão, se enviado via query string
        $versaoId = $request->query('versao');

        // Busca o template pelo nome
        $templateModel = Template::where(Template::COL_NOME, $template)->firstOrFail();
        
        // Se houver ID da versão, busca a versão específica
        // Caso contrário, carrega a versão mais recente
        $versao = $versaoId
            ? TemplateHistorico::where('id', $versaoId)
                ->where(TemplateHistorico::COL_TEMPLATE_ID, $templateModel->id)
                ->firstOrFail()
            : TemplateHistorico::where(TemplateHistorico::COL_TEMPLATE_ID, $templateModel->id)
                ->orderByDesc(TemplateHistorico::COL_CRIACAO)
                ->firstOrFail();
        
        // Retorna view do editor com o template e versão carregados
        return view('editor.index', compact('templateModel', 'versao'));
    }

    /**
     * Salva uma nova versão de um template e atualiza o conteúdo bruto da notícia.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function salvarTemplate(array $data): JsonResponse
    {
        // Salva a nova versão histórica do template
        $this->salvarVersaoTemplate(
            $data['nome'],
            $data['html']   ?? '',
            $data['css']    ?? '',
            $data['projeto']?? ''
        );

        // Retorna resposta de sucesso
        return response()->json(['success' => true]);
    }

    /**
     * Cria ou atualiza uma versão histórica do template.
     *
     * @param string $nome
     * @param string $html
     * @param string|null $css
     * @param string|null $projeto
     * @return void
     */
    public function salvarVersaoTemplate(string $nome, string $html, string $css = null, string $projeto = null): void
    {
        // Garante que o nome está preenchido
        $nome = trim($nome);
        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome inválido');
        }

        // Cria ou recupera o template pelo nome
        $template = Template::firstOrCreate(
            [Template::COL_NOME => $nome],
            [Template::COL_CRIACAO => now()]
        );

        // Registra uma nova entrada no histórico de templates
        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML        => $html,
            TemplateHistorico::COL_CSS         => $css        ?? '',
            TemplateHistorico::COL_PROJETO     => $projeto    ?? '',
        ]);
    }

    /**
     * Atualiza ou cria um template, convertendo o HTML para estrutura compatível com GrapesJS.
     *
     * @param string $nome
     * @param string $conteudoHtml
     * @return array
     */
    public function atualizarOuCriarTemplate(string $nome, string $conteudoHtml): array
    {
        // Normaliza o HTML encapsulando com div.container
        $htmlPadrao = '<div class="container">' . $conteudoHtml . '</div>';

        // Converte HTML bruto para componentes compatíveis com GrapesJS
        $components = $this->htmlToGrapesComponents($conteudoHtml);

        // Estrutura JSON do projeto GrapesJS
        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages' => [[
                'name' => $nome,
                'styles' => [],
                'frames' => [[
                    'component' => [
                        'tagName' => 'div',
                        'components' => $components,
                    ],
                ]],
            ]],
        ], JSON_UNESCAPED_UNICODE);

        // Salva a versão do template com estrutura GrapesJS
        $this->salvarVersaoTemplate($nome, $htmlPadrao, '', $gjsJson);

        // Retorna status de sucesso
        return ['success' => true, 'nome' => $nome];
    }

    /**
     * Converte o HTML bruto em uma estrutura de componentes compatível com GrapesJS.
     *
     * @param string $conteudoHtml
     * @return array
     */
    public function htmlToGrapesComponents(string $conteudoHtml): array
    {
        // Cria DOM fictício encapsulando o conteúdo
        $dom = new \DOMDocument();
        @$dom->loadHTML('<div>' . $conteudoHtml . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $body = $dom->getElementsByTagName('div')->item(0);

        $components = [];

        // Itera pelos nós do DOM, convertendo cada um
        foreach ($body->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                // Converte elemento para componente
                $components[] = $this->domNodeToGrapesComponent($node);
            } elseif ($node->nodeType === XML_TEXT_NODE) {
                // Mantém textos não vazios
                $text = trim($node->textContent);
                if ($text !== '') {
                    $components[] = $text;
                }
            }
        }

        return $components;
    }

    /**
     * Converte um nó do DOM em um componente compatível com GrapesJS.
     *
     * @param \DOMNode $node
     * @return array
     */
    public function domNodeToGrapesComponent(\DOMNode $node): array
    {
        $component = [
            'tagName' => $node->nodeName,
            'type' => $this->mapTagToType($node->nodeName),
        ];

        // Captura atributos do elemento
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $component['attributes'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        // Processa filhos recursivamente
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

    /**
     * Mapeia tags HTML comuns para tipos específicos usados pelo GrapesJS.
     *
     * @param string $tag
     * @return string
     */
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

        // Retorna mapeamento ou a própria tag
        return $map[$tag] ?? $tag;
    }

    /**
     * Carrega a última versão histórica do template, retornando via JSON.
     *
     * @param string $title
     * @return JsonResponse
     */
    public function carregarUltimaVersao($title)
    {
        // Busca template pelo nome
        $template = Template::where(Template::COL_NOME, $title)->firstOrFail();
        
        // Obtém a última versão histórica cadastrada
        $ultimoHistorico = TemplateHistorico::where(TemplateHistorico::COL_TEMPLATE_ID, $template->id)
            ->orderByDesc(TemplateHistorico::COL_CRIACAO)
            ->firstOrFail();

        // Retorna os dados da versão via JSON
        return response()->json([
            'html' => $ultimoHistorico->{TemplateHistorico::COL_HTML},
            'css' => $ultimoHistorico->{TemplateHistorico::COL_CSS},
            'projeto' => $ultimoHistorico->{TemplateHistorico::COL_PROJETO} ?? '{}',
        ]);
    }
}