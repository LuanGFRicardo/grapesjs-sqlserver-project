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
        Schema::create('templates', function (Blueprint $table) {
            // Chave primária
            $table->id();

            // Campos principais
            $table->string('nome', 255); // Nome conforme modelo
            $table->text('html')->nullable(); // HTML do template
            $table->text('css')->nullable(); // CSS do template
            $table->json('components')->nullable(); // Componentes GrapesJS
            $table->json('styles')->nullable(); // Estilos GrapesJS

            // Controle de datas
            $table->timestamp('data_cadastro')->default(DB::raw('CURRENT_TIMESTAMP')); // Data de cadastro
            $table->timestamp('data_exclusao')->nullable(); // Data de exclusão lógica

            // Removido: $table->timestamps();
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
