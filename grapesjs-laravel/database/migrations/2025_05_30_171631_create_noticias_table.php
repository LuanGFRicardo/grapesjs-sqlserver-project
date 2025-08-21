<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticiasTable extends Migration
{
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();

            // Dados da notícia
            $table->string('nome', 255)->comment('Título/identificador único da notícia');
            $table->text('descricao')->comment('Descrição resumida da notícia');
            $table->dateTime('data')->comment('Data da notícia');
            $table->dateTime('data_cadastro')->comment('Data de cadastro da notícia');
            $table->dateTime('data_expira')->nullable()->comment('Data de expiração da notícia (se aplicável)');
            $table->timestamp('data_exclusao')->nullable()->comment('Data de exclusão lógica');

            $table->string('autor', 255)->comment('Nome do autor da notícia');

            // Status da notícia
            $table->enum('status', ['publicado', 'rascunho', 'arquivado'])
                ->default('publicado')
                ->comment('Estado atual da notícia');
                
            // Imagens associadas
            $table->string('imagem_gr')->nullable()->comment('URL da imagem grande');
            $table->string('imagem_pq')->nullable()->comment('URL da imagem pequena');

            // Associação ao template
            $table->unsignedBigInteger('template_id')->comment('FK para template usado na notícia');

            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
                ->onDelete('cascade');

            $table->timestamps(); // created_at e updated_at padrão
        });
    }

    public function down()
    {
        Schema::dropIfExists('noticias');
    }
}
