<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')     
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->string('nama');
            $table->string('telepon');
            $table->text('alamat');
            $table->foreignId('produk_id')
                  ->constrained('produk')
                  ->onDelete('cascade');
            $table->string('produk_nama');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('total');
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai'])
                  ->default('Menunggu');
            $table->string('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};