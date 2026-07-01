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

class RecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $recommendations = [
            [
                'title' => 'Operasikan Rute Express Point-to-Point Pagi Hari',
                'description' => 'Tarik sebagian bus dari rute reguler untuk melayani perjalanan langsung tanpa transit dari simpul padat pinggiran (misal Lebak Bulus) menuju CBD (Sudirman) khusus pukul 06.00-08.30 untuk melayani Klaster C1.',
                'priority' => 'High',
                'status' => 'pending',
                'display_order' => 1,
            ],
            [
                'title' => 'Sterilisasi Jalur Busway Koridor Lintas Zona',
                'description' => 'Integrasikan CCTV cerdas dan tilang elektronik di Koridor 1, 6, dan 9 saat Peak Hour agar hambatan lalu lintas hilang. Target: Menurunkan TravelDuration Klaster C2 (Pejuang Lintas Zona) sebesar 20%.',
                'priority' => 'High',
                'status' => 'pending',
                'display_order' => 2,
            ],
            [
                'title' => 'Implementasi Diskon Tarif Off-Peak (Siang Hari)',
                'description' => 'Terapkan tarif diskon Rp 2.000 pada pukul 10.00-14.00 untuk memotivasi Klaster C3 (Fleksibel) beralih dari jam sibuk, sekaligus menambal Idle Capacity di siang hari.',
                'priority' => 'Medium',
                'status' => 'pending',
                'display_order' => 3,
            ],
            [
                'title' => 'Perbanyak Integrasi Feeder Mikrotrans pada Klaster C4',
                'description' => 'Lakukan re-alokasi armada Mikrotrans pada simpul-simpul ujung (end-node) mulai pukul 19.00-21.00 untuk memfasilitasi kebutuhan Klaster Loyalis Malam menyelesaikan perjalanan last-mile ke perumahan.',
                'priority' => 'Medium',
                'status' => 'pending',
                'display_order' => 4,
            ],
            [
                'title' => 'Terapkan Skema "Turn Around Cepat" (Empty Return) di Sore Hari',
                'description' => 'Untuk mengatasi Asymmetric Flow sore hari, instruksikan armada yang tiba di pinggiran luar Jakarta untuk kembali melalui jalur cepat/tol (tanpa berhenti) langsung ke CBD Sudirman guna menjemput kloter berikutnya.',
                'priority' => 'High',
                'status' => 'in_progress',
                'display_order' => 5,
            ],
            [
                'title' => 'Optimasi Headway Maksimal 2 Menit pada Rute Pendek CBD',
                'description' => 'Fokuskan pergerakan bus 2 menit sekali pada rute-rute dalam CBD Sudirman-Thamrin untuk melayani Klaster C1 (Komuter Cepat) yang sangat rentan beralih ke ojek online jika menunggu terlalu lama.',
                'priority' => 'High',
                'status' => 'pending',
                'display_order' => 6,
            ],
            [
                'title' => 'Re-alokasi Armada Akhir Pekan (Weekend Leisure)',
                'description' => 'Berdasarkan data perpindahan rute saat Weekend, geser 30% armada cadangan dari rute perkantoran menuju koridor wisata utama seperti Ragunan, Ancol, dan Monas.',
                'priority' => 'Medium',
                'status' => 'pending',
                'display_order' => 7,
            ],
            [
                'title' => 'Fasilitas Ramah Lansia (Low Deck) pada Rute Klaster Fleksibel',
                'description' => 'Tingginya metrik PayAmount Rp 0 oleh Lansia di luar jam sibuk harus direspon dengan percepatan pembaruan unit bus Low Deck (ramah disabilitas) pada koridor terkait.',
                'priority' => 'Low',
                'status' => 'completed',
                'display_order' => 8,
            ],
            [
                'title' => 'Kampanye Digital (CRM) Beralih Jam Keberangkatan',
                'description' => 'Kirimkan push notification melalui aplikasi TIJE dan layar halte, memberikan insentif poin bagi komuter (di luar Klaster C1) yang bersedia berangkat 30 menit lebih awal untuk memecah penumpukan.',
                'priority' => 'Low',
                'status' => 'pending',
                'display_order' => 9,
            ],
            [
                'title' => 'Evaluasi Penambahan Koridor Sub-Urban Lintas Provinsi',
                'description' => 'Analisis kepadatan yang ekstrem pada halte perbatasan (TapIn/TapOut Stops) dari Pejuang Lintas Zona membuktikan mendesaknya perluasan rute integrasi dengan Depok, Bekasi, dan Tangerang (Jabodetabek).',
                'priority' => 'Medium',
                'status' => 'pending',
                'display_order' => 10,
            ],
        ];

        DB::transaction(function () use ($recommendations, $now) {
            foreach ($recommendations as $rec) {
                DB::table('recommendations')->updateOrInsert(
                    ['title' => $rec['title']], 
                    array_merge($rec, [
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                );
            }
        });

        $this->command->info('Recommendations seeded successfully.');
    }
}
