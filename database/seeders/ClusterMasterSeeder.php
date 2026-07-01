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

class ClusterMasterSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $clusters = [
            [
                'cluster_code' => 'C1',
                'cluster_name' => 'Komuter Cepat Jarak Pendek',
                'description'  => 'Penumpang dengan perjalanan singkat (2-4 halte) yang dominan pada jam sibuk.',
                'color'        => '#2563EB',
                'icon'         => 'fa-bus',
            ],
            [
                'cluster_code' => 'C2',
                'cluster_name' => 'Pejuang Lintas Zona',
                'description'  => 'Penumpang dengan perjalanan panjang lintas koridor dengan durasi rata-rata di atas 60 menit.',
                'color'        => '#059669',
                'icon'         => 'fa-road',
            ],
            [
                'cluster_code' => 'C3',
                'cluster_name' => 'Pengguna Fleksibel Siang Hari',
                'description'  => 'Penumpang (seringkali tarif subsidi/gratis) yang lebih sering menggunakan layanan di luar jam sibuk (10.00-14.00).',
                'color'        => '#D97706',
                'icon'         => 'fa-clock',
            ],
            [
                'cluster_code' => 'C4',
                'cluster_name' => 'Loyalis Feeder dan Malam',
                'description'  => 'Penumpang armada pengumpan (Mikrotrans) dan perjalanan last-mile transit pada malam hari (setelah pukul 19.00).',
                'color'        => '#7C3AED',
                'icon'         => 'fa-moon',
            ],
        ];

        DB::transaction(function () use ($clusters, $now) {
            foreach ($clusters as $cluster) {
                DB::table('cluster_master')->updateOrInsert(
                    ['cluster_code' => $cluster['cluster_code']], // Kolom unik untuk upsert
                    array_merge($cluster, [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                );
            }
        });

        $this->command->info('Cluster Master seeded successfully.');
    }
}
