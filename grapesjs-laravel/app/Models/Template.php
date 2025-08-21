<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    // Tabela associada
    protected $table = 'templates';

    // Sem timestamps automáticos
    public $timestamps = false;

    // Campos permitidos para preenchimento em massa
    protected $fillable = [
        'nome', 
        'data_cadastro', 
        'data_exclusao'
    ];

    // Constantes para nomes de colunas
    public const COL_NOME = 'nome';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';

    public function historicos(): HasMany
    {
        return $this->hasMany(TemplateHistorico::class, TemplateHistorico::COL_TEMPLATE_ID);
    }
}
