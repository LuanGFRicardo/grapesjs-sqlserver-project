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
        'html',
        'css',
        'data_criacao',
        'data_modificacao',
        'data_exclusao'
    ];

    protected static function booted() 
    {
        static::addGlobalScope('ativo', fn($q) => $q->whereNull('data_exclusao'));
    }
}
