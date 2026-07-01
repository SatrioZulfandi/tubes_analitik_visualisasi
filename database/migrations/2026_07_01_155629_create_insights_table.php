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
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Wajib: Judul Insight');
            $table->text('description')->comment('Wajib: Penjelasan operasional dan bukti data');
            $table->enum('priority', ['High', 'Medium', 'Low'])->comment('Wajib: Nilai prioritas');
            $table->integer('display_order')->default(0)->comment('Opsional: Urutan tampil di dashboard');
            $table->boolean('is_active')->default(true)->comment('Opsional: Status aktif insight');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
