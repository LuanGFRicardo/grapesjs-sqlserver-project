<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Throwable;
use Log;

trait HandlesExceptions
{
    // Manipula exceções, registra no log e retorna JSON padrão
    public function handleException(string $contexto, \Throwable $e): JsonResponse
    {
        // Registra detalhes da exceção no log
        \Log::error($contexto, [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Retorna JSON padrão com erro interno
        return response()->json([
            'error' => 'Erro interno ao processar a chamada.'
        ], 500);
    }
}
