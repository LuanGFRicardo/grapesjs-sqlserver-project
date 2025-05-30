<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Template;

class TemplateHistorico extends Model
{
    // Tabela associada
    protected $table = 'template_historico';

    // Desativa timestamps automáticos
    public $timestamps = false;

    // Campos permitidos para preenchimento em massa
    protected $fillable = [
        'template_id',
        'html',
        'css',
        'projeto',
        'data_criacao',
        'data_modificacao',
        'data_exclusao'
    ];

    // Constantes para nomes de colunas
    public const COL_TEMPLATE_ID = 'template_id';
    public const COL_HTML = 'html';
    public const COL_CSS = 'css';
    public const COL_PROJETO = 'projeto';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';

    // Relação com Template
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, $this::COL_TEMPLATE_ID);
    }

    // Acesso rápido ao nome do template relacionado
    public function getTemplateNomeAttribute(): ?string
    {
        return $this->template?->nome;
    }
}
