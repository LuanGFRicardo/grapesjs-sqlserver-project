<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('template_historico', function (Blueprint $table) {
            $table->id();

            // FK para template original
            $table->unsignedBigInteger('template_id')
                ->comment('FK para template ativo');

            // Conteúdo da versão histórica
            $table->longText('html')->comment('HTML salvo na versão histórica');
            $table->longText('css')->nullable()->comment('CSS salvo na versão histórica');
            $table->longText('projeto')->comment('JSON completo do projeto GrapesJS');

            // Controle temporal
            $table->timestamp('data_criacao')
                ->nullable()
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('Data de criação da versão');
            $table->timestamp('data_modificacao')
                ->nullable()
                ->useCurrentOnUpdate()
                ->comment('Última modificação da versão');
            $table->timestamp('data_exclusao')->nullable()->comment('Exclusão lógica');

            // Integridade referencial
            $table->foreign('template_id')
                  ->references('id')
                  ->on('templates')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_historico');
    }
};
