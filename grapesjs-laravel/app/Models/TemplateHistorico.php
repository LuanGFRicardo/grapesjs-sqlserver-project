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

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}