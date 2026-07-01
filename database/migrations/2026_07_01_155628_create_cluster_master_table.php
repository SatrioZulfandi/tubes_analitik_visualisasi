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
        Schema::create('cluster_master', function (Blueprint $table) {
            $table->id();
            $table->string('cluster_code', 10)->unique()->comment('Wajib: Kode referensi klaster (misal: C1, C2)');
            $table->string('cluster_name')->comment('Wajib: Nama representatif klaster');
            $table->text('description')->nullable()->comment('Opsional: Penjelasan karakteristik');
            $table->string('color', 20)->nullable()->comment('Opsional: Warna hex untuk chart');
            $table->string('icon', 100)->nullable()->comment('Opsional: Ikon/FontAwesome class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster_master');
    }
};
