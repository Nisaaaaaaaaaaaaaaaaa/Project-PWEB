<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('petanis', function (Blueprint $table) {
            $table->string('telepon')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('petanis', function (Blueprint $table) {
            $table->dropColumn('telepon');
        });
    }
};
