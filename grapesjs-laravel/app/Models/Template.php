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

    public function historicos(): HasMany
    {
        return $this->hasMany(TemplateHistorico::class, 'template_id');
    }
}
