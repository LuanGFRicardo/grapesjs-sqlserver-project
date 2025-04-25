<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateHistorico;

class GrapesEditorController extends Controller
{
    public function index(Request $request, $template)
    {
        $versaoId = $request->query('versao');
    
        $templateModel = Template::where('nome', $template)->firstOrFail();
    
        // Recupera a versão específica ou a última salva
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
                'css' => 'nullable|string',
                'projeto' => 'nullable|string',
            ]);

            \Log::info('Dados validados:', $validated);

            // Cria ou obtém o template principal
            $template = Template::firstOrCreate(
                ['nome' => $validated['nome']],
                ['data_cadastro' => now()]
            );

            // Salva nova versão no histórico
            TemplateHistorico::create([
                'template_id' => $template->id,
                'html' => $validated['html'] ?? '',
                'css' => $validated['css'] ?? '',
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

        // Recupera a versão mais recente do template
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
        // Retorna lista de templates
        $templates = Template::all(['id', 'nome']);
        return view('grapes-editor-menu', compact('templates'));
    }

    // Faz upload de imagem
    public function uploadImagem(Request $request) {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');
            $url = Storage::url($path);
            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }

    // Deleta a imagem
    // public function deletarImagem(Request $request) {
    //     $url = $request->input('url');

    //     // Extrai o caminho relativo
    //     $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));

    //     if (Storage::disk('public')->exists($relativePath)) {
    //         Storage::disk('public')->delete($relativePath);
    //         return response()->json(['success' => true]);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Arquivo não encontrado.']);
    // }

    // Baixar o template
    public function baixarTemplate(Request $request) {
        // Limpa arquivos antigos
        $files = glob(storage_path('app/tmp/*.zip'));
        $expiracao = now()->subMinutes(30)->timestamp;
        foreach ($files as $file) {
            if (filemtime($file) < $expiracao) {
                unlink($file);
            }
        }
    
        $templateId = $request->input('template_id');
    
        $templateNome = Template::where('id', $templateId)
            ->firstOrFail();
    
        if (!$templateNome) {
            return response()->json(['erro' => 'Template não encontrado.'], 400);
        }
    
        // Gera nome e caminho do arquivo
        $nomeProjeto = $templateNome ?: 'template';
        $nomeArquivo = Str::slug($nomeProjeto, '_') . '_' . now()->format('Ymd_His') . '.zip';
        $path = storage_path("app/tmp/{$nomeArquivo}");
    
        // Garante que o diretório exista
        $dirTmp = dirname($path);
        if (!is_dir($dirTmp)) {
            if (!mkdir($dirTmp, 0755, true)) {
                \Log::error("Não foi possível criar o diretório: {$dirTmp}");
                return response()->json(['erro' => 'Falha ao criar diretório temporário.'], 500);
            }
        }
    
        \Log::info("Tentando criar o arquivo ZIP: {$path}");
    
        try {
            $zip = new ZipArchive;
            $res = $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            if ($res !== TRUE) {
                \Log::error("ZipArchive::open falhou com código: {$res}");
                return response()->json(['erro' => 'Erro ao criar o arquivo.'], 500);
            }

            $historico = TemplateHistorico::where('template_id', $templateId)
            ->latest('data_criacao')
            ->first();

            if (!$historico) {
                return response()->json(['erro' => 'Histórico de template não encontrado.'], 400);
            }
    
            $htmlOriginal = $historico->html ?? '';
            $htmlHead = $this->inserirCssHead($htmlOriginal);

            $cssSanitizado = html_entity_decode($historico->css ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');         

            $zip->addFromString("template.blade.php", $htmlHead);
            $zip->addFromString("style.css", $cssSanitizado);
            $zip->close();
        } catch (\Exception $e) {
            \Log::error('Erro ao criar zip do template:', [
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['erro' => 'Erro inesperado ao gerar o zip.'], 500);
        }        
    
        return response()->download($path)->deleteFileAfterSend(true);
    }

    // Insere o estilo no HTML
    private function inserirCssHead(string $html): string
    {
        try {
            $linksCss = [
                '{{ asset(\'css/template/style.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/googleapis/css/googleapiscss.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/tailwindcss/css/tailwind-build.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/tailwindcss/css/base.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/tailwindcss/css/components.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/tailwindcss/css/tailwind.min.css\') }}' . '">' . PHP_EOL,
                '{{ asset(\'vendor/tailwindcss/css/utilities.css\') }}' . '">'
            ];

            $headContent = '';
            foreach ($linksCss as $href) {
                $headContent .= '<link rel="stylesheet" href="' . $href;
            }
            
            // Verifica se há uma tag <head>
            if (stripos($html, '<head>') !== false) {
                return preg_replace('/<head>/i', '<head>' . PHP_EOL . $headContent, $html, 1);
            }
        
            // Se não houver <head>, tenta inserir dentro de <html>
            if (stripos($html, '<html>') !== false) {
                return preg_replace(
                    '/<html[^>]*>/i',
                    '$0' . PHP_EOL . '<head>' . PHP_EOL . $headContent . PHP_EOL . '</head>',
                    $html,
                    1
                );
            }
        
            // Se não houver nem <html>, insere manualmente no topo
            return '<!DOCTYPE html>' . PHP_EOL .
                '<html>' . PHP_EOL .
                '<head>' . PHP_EOL .
                $headContent . PHP_EOL .
                '</head>' . PHP_EOL .
                '<body>' . PHP_EOL .
                $html . PHP_EOL .
                '</body>' . PHP_EOL .
                '</html>';
        } catch (\Exception $e) {
            \Log::error('Erro:', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro.'], 500);
        }                
    }

    // Redireciona para template exemplar
    public function template()
    {
        return view('template');
    }
}
