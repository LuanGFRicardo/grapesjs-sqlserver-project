<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}
