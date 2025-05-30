<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    // Define a tabela associada
    protected $table = 'componentes';

    // Desativa timestamps automáticos
    public $timestamps = false;

    // Campos que podem ser preenchidos em massa
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

    // Constantes para os nomes das colunas
    public const COL_NOME = 'nome';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';
}
