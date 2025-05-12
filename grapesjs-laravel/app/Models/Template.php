<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $table = 'templates';
    public $timestamps = false;

    protected $fillable = [
        'nome', 
        'data_cadastro', 
        'data_exclusao'
    ];

    public const COL_NOME = 'nome';
    public const COL_CRIACAO = 'data_criacao';
    public const COL_MODIFICACAO = 'data_modificacao';
    public const COL_EXCLUSAO = 'data_exclusao';
}
