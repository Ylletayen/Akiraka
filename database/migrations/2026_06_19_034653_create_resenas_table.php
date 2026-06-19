<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración (Crea la tabla).
     */
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id(); // Crea una llave primaria auto-incremental
            $table->string('nombre_cliente'); // Para el nombre del usuario
            $table->text('comentario'); // Tipo text porque los comentarios pueden ser largos
            $table->tinyInteger('calificacion')->default(5); // Un número chiquito del 1 al 5
            
            // ========================================================
            // COLUMNAS PARA EL SISTEMA DE VOTOS DE LA COMUNIDAD
            // ========================================================
            $table->integer('votos_count')->default(0);
            $table->integer('estrellas_sum')->default(0);

            $table->timestamps(); // Crea automáticamente las columnas 'created_at' y 'updated_at'
        });
    }

    /**
     * Revierte la migración (Elimina la tabla si nos arrepentimos).
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};