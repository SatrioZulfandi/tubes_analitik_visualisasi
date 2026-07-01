<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Seeder Version
|--------------------------------------------------------------------------
|
| Version : 1.0
| Project : Dashboard BI Transjakarta
| Author  : Satrio
|
*/

class InsightSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $insights = [
            [
                'title' => 'Anomali Kepadatan Ekstrem Pagi Hari di Halte Transit',
                'description' => 'Berdasarkan ekstraksi fitur TapInHour, terjadi lonjakan volume penumpang hingga 300% pada rentang waktu 06.00-08.00 (Peak Hour). Penumpang ini mayoritas bergerak dari koridor luar (Cluster 2) menuju halte transit pusat kota.',
                'priority' => 'High',
                'display_order' => 1,
            ],
            [
                'title' => 'Klaster Pejuang Lintas Zona Memiliki Durasi Anomali',
                'description' => 'Hasil pemetaan K-Means menunjukkan Cluster 2 (Pejuang Lintas Zona) mencatatkan TravelDuration rata-rata 75 menit. Ini berkolerasi dengan antrean kemacetan di persimpangan koridor perbatasan seperti Lebak Bulus dan Kampung Rambutan.',
                'priority' => 'High',
                'display_order' => 2,
            ],
            [
                'title' => 'Idle Capacity Ekstrem Siang Hari',
                'description' => 'Dari pukul 10.00 hingga 14.00, tingkat keterisian bus (StopsPassed vs Volume) turun drastis. Cluster 3 (Pengguna Fleksibel) tidak cukup masif untuk menyerap suplai armada yang masih beroperasi 100%.',
                'priority' => 'Medium',
                'display_order' => 3,
            ],
            [
                'title' => 'Dominasi Absolut Pekerja Dewasa pada Jam Sibuk',
                'description' => 'Korelasi bivariat fitur Age dan PeakHour membuktikan bahwa 78% pengguna layanan di jam sibuk adalah kelompok usia produktif (22-45 tahun), menjadikan layanan Tije sangat sensitif pada jadwal operasional kantor.',
                'priority' => 'Medium',
                'display_order' => 4,
            ],
            [
                'title' => 'Koridor 1 Tetap Menjadi Tulang Punggung (Backbone)',
                'description' => 'Koridor Blok M - Kota konsisten menduduki Top 1 Traffic harian. Distribusi jarak tempuhnya terdistribusi merata, yang menandakan bahwa pola "naik-turun cepat" sangat kental di rute ini.',
                'priority' => 'High',
                'display_order' => 5,
            ],
            [
                'title' => 'Potensi Layanan First/Last Mile Malam Hari',
                'description' => 'Pola Tap Out pada malam hari dari Cluster 4 (Loyalis Feeder) secara masif tertuju pada halte-halte perbatasan permukiman. Ini membuktikan tingginya urgensi layanan pengumpan Mikrotrans di atas pukul 19.00.',
                'priority' => 'Medium',
                'display_order' => 6,
            ],
            [
                'title' => 'Aktivitas Akhir Pekan (Weekend Leisure Travel)',
                'description' => 'Meskipun total komuter turun 45% pada hari Sabtu/Minggu (DayType = Weekend), durasi perjalanan penumpang justru lebih tinggi. Mereka mengarah ke simpul rekreasi dan perbelanjaan (Ragunan, Bundaran HI).',
                'priority' => 'Medium',
                'display_order' => 7,
            ],
            [
                'title' => 'Efisiensi Transaksi Komuter Cepat (Cluster 1)',
                'description' => 'Segmentasi Cluster 1 (Komuter Cepat) mencetak jarak tempuh rata-rata terpendek (2-4 halte). Kecepatan perpindahan tap-in/tap-out ini menunjukkan mobilitas ultra-mikro antar kawasan CBD Sudirman.',
                'priority' => 'High',
                'display_order' => 8,
            ],
            [
                'title' => 'Ketimpangan Arus (Asymmetric Flow) Sore Hari',
                'description' => 'Terdapat ketimpangan arah bus di jam 17.00. Bus dari Sudirman menuju daerah pinggiran (Depok/Bekasi) over-capacity, sementara bus yang kembali dari arah luar kota menuju Sudirman hampir tidak ada penumpang (Asymmetric Flow).',
                'priority' => 'High',
                'display_order' => 9,
            ],
            [
                'title' => 'Subsidi Tarif Efektif Menyasar Klaster 3 (Lansia/Non-Pekerja)',
                'description' => 'Fitur PayAmount senilai Rp 0 memiliki korelasi 85% dengan rentang usia lansia (>60 tahun) dan pelajar yang secara konsisten bepergian pada waktu Off-Peak.',
                'priority' => 'Low',
                'display_order' => 10,
            ],
        ];

        DB::transaction(function () use ($insights, $now) {
            foreach ($insights as $insight) {
                DB::table('insights')->updateOrInsert(
                    ['title' => $insight['title']], 
                    array_merge($insight, [
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                );
            }
        });

        $this->command->info('Insights seeded successfully.');
    }
}
