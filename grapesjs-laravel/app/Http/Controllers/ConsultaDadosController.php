<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultaDadosController extends Controller
{
    // Retorna dados conforme tipo ou erro
    public function obter($tipo)
    {
        try {
            $dados = $this->buscarDadosPorTipo($tipo);

            if (is_null($dados)) {
                return response()->json(['erro' => 'Tipo inválido'], 400);
            }

            return response()->json($dados);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar dados do tipo [$tipo]: " . $e->getMessage());
            return response()->json(['erro' => 'Erro interno ao buscar dados.'], 500);
        }
    }

    // Consulta dados segundo o tipo
    private function buscarDadosPorTipo(string $tipo)
    {
        return match ($tipo) {
            'noticia' => DB::table('noticias')->select('nome as valor')->get(),
            default => null,
        };
    }
}
