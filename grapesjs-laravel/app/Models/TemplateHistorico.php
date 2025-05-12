<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateHistorico extends Model
{
    protected $table = 'template_historico';

    public $timestamps = false;

    protected $fillable = [
        'template_id',
        'html',
        'css',
        'projeto',
        'data_criacao',
        'data_modificacao',
        'data_exclusao'
    ];

    public const COL_TEMPLATE_ID = 'template_id';
    public const COL_HTML = 'html';
    public const COL_CSS = 'css';
    public const COL_PROJETO = 'projeto';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';
}
