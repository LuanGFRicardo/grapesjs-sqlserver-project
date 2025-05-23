<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\HtmlManipulatorService;
use ZipArchive;
use Exception;
use App\Models\Template;
use App\Models\TemplateHistorico;

class FileService
{
    protected HtmlManipulatorService $htmlManipulatorService;

    public function __construct(HtmlManipulatorService $htmlManipulatorService)
    {
        $this->htmlManipulatorService = $htmlManipulatorService;
    }

    // Faz upload de imagem e retorna o caminho salvo
    public function uploadImagem(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public');
            return response()->json([
                'url' => Storage::url($path),
            ]);
        }

        return null;
    }

    // Gera um arquivo ZIP com o template e retorna o caminho do arquivo
    public function gerarTemplateZip(int $templateId): ?string
    {
        $this->limparArquivosTemporarios();

        $template = Template::find($templateId);

        if (!$template) {
            throw new Exception('Template não encontrado.');
        }

        $nomeProjeto = $template->nome ?? 'template';
        $nomeArquivo = Str::slug($nomeProjeto, '_') . '_' . now()->format('Ymd_His') . '.zip';
        $path = storage_path("app/tmp/{$nomeArquivo}");

        $this->ensureDirectoryExists(dirname($path));

        $historico = TemplateHistorico::where('template_id', $templateId)
            ->latest(TemplateHistorico::COL_CRIACAO)
            ->first();

        if (!$historico) {
            throw new Exception('Histórico de template não encontrado');
        }

        $htmlComCss = $this->htmlManipulatorService->inserirCssHead($historico->html ?? '');
        $cssSanitizado = html_entity_decode($historico->css ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Erro ao criar arquivo ZIP.');
        }

        $zip->addFromString("template.blade.php", $htmlComCss);
        $zip->addFromString("style.css", $cssSanitizado);
        $zip->close();

        return $path;
    }

    //  Remove arquivos ZIP antigos
    private function limparArquivosTemporarios(): void
    {
        $files = glob(storage_path('app/tmp/*.zip'));
        $expirationTime = now()->subMinutes(30)->timestamp;

        foreach ($files as $file) {
            if (filemtime($file) < $expirationTime) {
                @unlink($file);
            }
        }
    }

    // Garante que o diretório existe
    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true) && !id_dir($path)) {
                throw new Exception("Falha ao criar diretório: {$path}");
            }
        }
    }
}