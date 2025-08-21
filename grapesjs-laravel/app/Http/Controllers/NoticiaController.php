<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Controller para o blade do template experimental de notícias
class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::where('status', 'publicado')
            ->orderBy('data', 'desc')
            ->paginate(9);

        return view('noticias.index', compact('noticias'));
    }

    public function show($id)
    {
        $noticia = Noticia::with(['template'])
            ->where('id', $id)
            ->where('status', 'publicado')
            ->firstOrFail();

        // Buscar últimas 3 outras notícias
        $noticiasRelacionadas = Noticia::where('status', 'publicado')
            ->where('id', '!=', $id)
            ->orderBy('data', 'desc')
            ->take(3)
            ->get();

        // Carrega o último template histórico do template associado
        $templateHistorico = $noticia->template
            ? $noticia->template->hasMany(\App\Models\TemplateHistorico::class)
                ->orderByDesc('data_criacao')
                ->first()
            : null;

        return view('noticias.show', compact('noticia', 'noticiasRelacionadas', 'templateHistorico'));
    }
}
