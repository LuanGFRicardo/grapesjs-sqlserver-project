<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Componente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComponenteController extends Controller
{
    // Exibe a tela de gerenciamento de componentes
    public function index()
    {
        $componentes = Componente::orderBy('nome')->get();
        return view('components-manager', compact('componentes'));
    }
    
    // Retorna os dados de um componente específico
    public function buscarComponente($id)
    {
        $componente = Componente::findOrFail($id);
        return response()->json($componente);
    }

    // Cria um novo componente
    public function salvarComponente(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:componentes,nome',
            'categoria' => 'nullable|string|max:255',
            'html' => 'required|string',
            'css' => 'nullable|string',
        ]);

        $validated['data_criacao'] = now();
        $componente = Componente::create($validated);

        return response()->json($componente, 201);
    }

    // Atualiza um componente existente
    public function atualizarComponente(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:componentes,nome,' . $id,
            'categoria' => 'nullable|string|max:255',
            'html' => 'required|string',
            'css' => 'nullable|string',
        ]);

        $validated['data_modificacao'] = now();
        $componente->update($validated);

        return response()->json($componente);
    }

    // Marca um componente como excluído
    public function excluirComponente($id)
    {
        $componente = Componente::findOrFail($id);
        $componente->data_exclusao = now();
        $componente->save();

        return response()->json(['status' => 'ok']);
    }

    // Retorna todos os componentes não excluídos
    public function listarComponentes() {
        return Componente::whereNull('data_exclusao')->get();
    }
}
