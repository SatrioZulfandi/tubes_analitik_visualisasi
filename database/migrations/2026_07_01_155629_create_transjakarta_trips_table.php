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
        Schema::create('transjakarta_trips', function (Blueprint $table) {
            $table->id();
            
            // Kolom dari Dataset
            $table->string('trans_id')->unique()->nullable()->comment('Opsional: ID tiket (sudah didrop saat cleaning, disisakan nullable/unique untuk kompatibilitas)');
            $table->string('pay_card_bank')->nullable()->comment('Opsional: Bank penerbit kartu (nullable jika unknown)');
            $table->string('pay_card_type')->nullable()->comment('Opsional: Tipe kartu tiket (nullable jika unknown)');
            $table->string('pay_card_sex', 20)->nullable()->comment('Opsional: Jenis kelamin penumpang');
            $table->integer('pay_card_birth_date')->nullable()->comment('Opsional: Tahun kelahiran');
            $table->string('corridor_name')->nullable()->comment('Wajib (secara operasional): Rute');
            $table->dateTime('tap_in_time')->nullable()->comment('Opsional: Waktu mentah tap in');
            $table->dateTime('tap_out_time')->nullable()->comment('Opsional: Waktu mentah tap out');
            
            // Metrik Numerik (Wajib)
            $table->decimal('pay_amount', 10, 2)->comment('Wajib: Tarif. Fitur ML K-Means');
            $table->integer('age')->comment('Wajib: Hasil konversi umur. Fitur ML K-Means');
            $table->string('age_group', 50)->nullable()->comment('Opsional: Kategori umur (Dewasa, Lansia, dll)');
            $table->decimal('travel_duration', 8, 2)->comment('Wajib: Durasi di dalam bus (menit). Fitur ML K-Means');
            $table->integer('tap_in_hour')->comment('Wajib: Jam ekstraksi keberangkatan (0-23). Fitur ML K-Means');
            $table->string('time_category', 50)->nullable()->comment('Opsional: Kategori waktu (Pagi, Siang)');
            $table->tinyInteger('peak_hour')->comment('Wajib: Biner 1/0. Fitur ML K-Means');
            $table->string('day_name', 20)->nullable()->comment('Opsional: Nama hari');
            $table->string('day_type', 20)->nullable()->comment('Opsional: Weekday/Weekend');
            $table->integer('stops_passed')->comment('Wajib: Jumlah halte yang dilewati. Fitur ML K-Means');
            $table->string('travel_type', 50)->nullable()->comment('Opsional: Transit/Direct');
            
            // Relasi Cluster ML
            $table->unsignedBigInteger('cluster_id')->comment('Wajib: Hasil segmen K-Means (0,1,2,3)');

            $table->timestamps();

            // Foreign Key
            $table->foreign('cluster_id')
                  ->references('id')
                  ->on('cluster_master')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            // Indexing Optimasi Query Dashboard
            $table->index('cluster_id');
            $table->index('day_type');
            $table->index('peak_hour');
            $table->index('corridor_name');
            $table->index('tap_in_hour');
            $table->index('travel_type');
            $table->index('pay_amount');

            // Composite Indexes (Dashboard Filtering)
            $table->index(['cluster_id', 'day_type']);
            $table->index(['cluster_id', 'peak_hour']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transjakarta_trips');
    }
};
