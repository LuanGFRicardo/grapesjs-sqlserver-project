<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('componentes', function (Blueprint $table) {
            $table->id()->comment('Identificador único do componente');

            // Informações básicas do componente
            $table->string('nome', 255)->comment('Nome descritivo do componente');
            $table->string('categoria', 100)->nullable()->comment('Categoria funcional ou agrupamento');
            $table->string('icone', 100)->nullable()->comment('Classe ou referência ao ícone (ex: FontAwesome)');

            // Conteúdo visual do componente
            $table->text('html')->nullable()->comment('Código HTML do componente');
            $table->text('css')->nullable()->comment('Código CSS personalizado');

            // Datas de controle
            $table->timestamp('data_criacao')
                  ->nullable()
                  ->default(DB::raw('CURRENT_TIMESTAMP'))
                  ->comment('Data de criação do componente');

            $table->timestamp('data_modificacao')
                  ->nullable()
                  ->default(DB::raw('CURRENT_TIMESTAMP'))
                  ->comment('Última data de modificação do componente');

            $table->timestamp('data_exclusao')
                  ->nullable()
                  ->comment('Data de exclusão lógica, se aplicável');

            $table->index('categoria', 'componentes_categoria_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('componentes');
    }
};
