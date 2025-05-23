<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Services\HtmlManipulatorService;
use App\Models\Institucional;

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
        
        return view('editor.index', compact('templateModel', 'versao'));
    }

    public function criarTemplate(string $nome, string $conteudoHtml): array
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

        $htmlPadrao = '<div class="container">' . $conteudoHtml . '</div>';
        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages' => [[
                'name' => $nome,
                'styles' => [],
                'frames' => [[
                    'component' => [
                        'tagName' => 'div',
                        'components' => [
                            [
                                'type' => 'text',
                                'content' => $conteudoHtml,
                            ],
                        ],
                    ]
                ]],
            ]],
        ], JSON_UNESCAPED_UNICODE);

        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML => $htmlPadrao,
            TemplateHistorico::COL_PROJETO => $gjsJson,
        ]);

        return ['success' => true, 'nome' => $nome];  
    }

    public function salvarTemplate(array $data): JsonResponse
    {
        // 1) Salva a nova versão histórica
        $this->salvarVersaoTemplate(
            $data['nome'],
            $data['html']   ?? '',
            $data['css']    ?? '',
            $data['projeto']?? ''
        );

        // 2) Atualiza o campo bruto da notícia
        //    Procuramos o registro em institucional onde o título bate com o template
        Institucional::where('Institucional_nome', $data['nome'])
            ->update([
                'Institucional_texto' => $data['html'] ?? '',
            ]);

        return response()->json(['success' => true]);
    }

    public function salvarVersaoTemplate(string $nome, string $html, string $css = null, string $projeto = null): void
    {
        $nome = trim($nome);
        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome inválido');
        }

        // Cria ou obtém o template
        $template = Template::firstOrCreate(
            [Template::COL_NOME => $nome],
            [Template::COL_CRIACAO => now()]
        );

        // Cria uma nova versão histórica
        TemplateHistorico::create([
            TemplateHistorico::COL_TEMPLATE_ID => $template->id,
            TemplateHistorico::COL_HTML        => $html,
            TemplateHistorico::COL_CSS         => $css        ?? '',
            TemplateHistorico::COL_PROJETO     => $projeto    ?? '',
        ]);

        // Atualiza raw da notícia
        Institucional::where('Institucional_nome', $nome)
            ->update(['Institucional_texto' => $html]);
    }

    public function atualizarOuCriarTemplate(string $nome, string $conteudoHtml): array
    {
        // Normaliza o HTML para a versão “padrão” do CRUD
        $htmlPadrao = '<div class="container">'.$conteudoHtml.'</div>';

        // JSON mínimo pro editor (pode ser estendido se você quiser extrair CSS etc)
        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages'  => [[
                'name'   => $nome,
                'styles' => [],
                'frames' => [[
                    'component' => [
                        'tagName'   => 'div',
                        'components'=> [[
                            'type'    => 'text',
                            'content' => $conteudoHtml,
                        ]],
                    ],
                ]],
            ]],
        ], JSON_UNESCAPED_UNICODE);

        // Reaproveita o método genérico
        $this->salvarVersaoTemplate($nome, $htmlPadrao, '', $gjsJson);

        return ['success' => true, 'nome' => $nome];
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