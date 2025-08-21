<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();

            // Campos principais do template
            $table->string('nome', 255)->comment('Nome identificador do template');
            $table->text('html')->nullable()->comment('Código HTML bruto do template');
            $table->text('css')->nullable()->comment('CSS personalizado do template');
            $table->json('components')->nullable()->comment('Componentes estruturais GrapesJS');
            $table->json('styles')->nullable()->comment('Estilos detalhados GrapesJS');

            // Controle temporal com data de criação e exclusão
            $table->timestamp('data_cadastro')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('Data de criação do template');
            $table->timestamp('data_exclusao')->nullable()->comment('Data da exclusão lógica');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
