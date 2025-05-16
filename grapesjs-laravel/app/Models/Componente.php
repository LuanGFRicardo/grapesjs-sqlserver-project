<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $table = 'componentes';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'categoria',
        'icone',
        'html',
        'css',
        'data_criacao',
        'data_modificacao',
        'data_exclusao'
    ];

    public const COL_NOME = 'nome';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';
}
