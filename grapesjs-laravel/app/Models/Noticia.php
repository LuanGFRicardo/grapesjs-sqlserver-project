<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    // Tabela associada
    protected $table = 'noticias';

    // Campos mass assignable
    protected $fillable = [
        'nome',
        'descricao',
        'data',
        'data_cadastro',
        'data_expira',
        'data_exclusao',
        'autor',
        'status',
        'imagem_gr',
        'imagem_pq',
        'template_id',
    ];

    // Conversão automática para datetime
    protected $casts = [
        'data' => 'datetime',
        'data_cadastro' => 'datetime',
        'data_expira' => 'datetime',
        'data_exclusao' => 'datetime',
    ];

    // Relação inversa com Template
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}
