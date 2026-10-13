<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 150);
            $table->string('tipo', 20);
            $table->string('pack', 50)->nullable();
            $table->string('origen', 20)->default('directo')->index();
            $table->text('mensaje');
            $table->string('estado', 20)->default('nuevo')->index();
            $table->string('correo_estado', 20)->default('pendiente');
            $table->decimal('presupuesto', 12, 2)->nullable();
            $table->decimal('venta', 12, 2)->nullable();
            $table->decimal('coste', 12, 2)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
