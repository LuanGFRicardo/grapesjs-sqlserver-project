<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institucional extends Model
{
    // Nome da tabela explicitamente
    protected $table = 'institucional';

    // Chave primária
    protected $primaryKey = 'Institucional_id';

    // Não usa timestamps automáticos
    public $timestamps = false;

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'idioma_id',
        'pais_id',
        'Funcionarios_id',
        'Mascara_id',
        'secao_id_principal',
        'estilo_nome',
        'Institucional_nome',
        'Institucional_campo',
        'Institucional_url',
        'Institucional_link',
        'Institucional_tipo',
        'Institucional_descricao',
        'Institucional_image_largura',
        'Institucional_image_altura',
        'Institucional_data',
        'Institucional_data_cadastro',
        'Institucional_data_expira',
        'Institucional_data_inicial',
        'Institucional_status',
        'Institucional_fonte',
        'Institucional_autor',
        'Institucional_autor_id',
        'Institucional_legenda',
        'Institucional_texto',
        'Institucional_imagem_pq',
        'Institucional_imagem_gr',
        'Institucional_arquivo',
        'Institucional_arquivo_tamanho',
        'Institucional_arquivo2',
        'Institucional_arquivo2_tamanho',
        'INSTITUCIONAL_VAL_CONTEUDO',
        'Institucional_excluir',
        'INSTITUCIONAL_ID_ALTERACAO',
        'Institucional_comentarios',
        'institucional_servico_unidade_movel',
        'Institucional_visitas'
    ];
}
