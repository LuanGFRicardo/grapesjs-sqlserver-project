<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Executa a migration.
     */
    public function up()
    {
        Schema::create('template_historico', function (Blueprint $table) {
            // Chave primária
            $table->id(); // unsignedBigInteger auto_increment

            // Chave estrangeira
            $table->unsignedBigInteger('template_id');

            // Campos de conteúdo
            $table->longText('html'); // HTML salvo
            $table->longText('css')->nullable(); // CSS salvo
            $table->longText('projeto'); // JSON do projeto GrapesJS

            // Datas de controle
            $table->timestamp('data_criacao')->nullable()->default(DB::raw('CURRENT_TIMESTAMP')); // Criação
            $table->timestamp('data_modificacao')->nullable()->useCurrentOnUpdate(); // Modificação
            $table->timestamp('data_exclusao')->nullable(); // Exclusão lógica

            // Constraint de chave estrangeira
            $table->foreign('template_id') // FK template_id → templates(id)
                  ->references('id')
                  ->on('templates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_historico');
    }
};
