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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Wajib: Judul Rekomendasi/Solusi');
            $table->text('description')->comment('Wajib: Rincian aksi operasional');
            $table->enum('priority', ['High', 'Medium', 'Low'])->comment('Wajib: High, Medium, Low');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->comment('Opsional: Status eksekusi');
            $table->integer('display_order')->default(0)->comment('Opsional: Urutan tampil di Kanban');
            $table->boolean('is_active')->default(true)->comment('Opsional: Status aktif/tampil');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
