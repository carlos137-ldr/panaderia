<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('branch_id')->constrained('branches');
            $table->dateTime('fecha_pedido')->default(now());
            $table->dateTime('fecha_recogida');
            $table->enum('estado', ['pendiente', 'preparando', 'listo', 'entregado'])->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
