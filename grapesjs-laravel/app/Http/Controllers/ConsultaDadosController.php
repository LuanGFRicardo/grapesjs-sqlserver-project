<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultaDadosController extends Controller
{
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

    /**
     * Mapeia o tipo de consulta e executa a query apropriada.
     *
     * @param string $tipo
     * @return \Illuminate\Support\Collection|null
     */
    private function buscarDadosPorTipo(string $tipo)
    {
        return match ($tipo) {
            'registro', 'lista-registros' => DB::table('SCDA01')->select('Num_Registro')->get(),
            default => null,
        };
    }
}
