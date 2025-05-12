<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use App\Enums\StatusErro;
use Throwable;
use Log;

trait HandlesExceptions
{
    public function handleException(string $contexto, \Throwable $e): JsonResponse
    {
        \Log::error($contexto, [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => StatusErro::INTERNO
        ], 500);        
    }
}