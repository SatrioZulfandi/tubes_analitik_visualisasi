<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SplFileObject;
use Exception;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Seeder Version
|--------------------------------------------------------------------------
|
| Version : 1.1
| Project : Dashboard BI Transjakarta
| Author  : Satrio
| Description: Import Fact Data CSV. Uses Truncate+Insert due to dropped trans_id.
|
*/

class TransjakartaTripSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $csvFilePath = database_path('dataset/dfTransjakarta_cluster.csv');

            // 1. Validasi Keberadaan File
            if (!File::exists($csvFilePath)) {
                throw new Exception("File CSV tidak ditemukan pada path: {$csvFilePath}");
            }

            // 2. Pemetaan Dinamis Cluster
            $clusterMaster = DB::table('cluster_master')->pluck('id', 'cluster_code')->toArray();
            if (empty($clusterMaster)) {
                throw new Exception("Tabel cluster_master kosong. Jalankan ClusterMasterSeeder terlebih dahulu!");
            }

            $pythonToCodeMap = [
                '0' => 'C1',
                '1' => 'C2',
                '2' => 'C3',
                '3' => 'C4',
            ];

            // 3. Inisialisasi Pembacaan
            $file = new SplFileObject($csvFilePath, 'r');
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

            // 4. Validasi Seluruh Header Esensial
            $header = $file->fgetcsv();
            $requiredHeaders = [
                'payCardBank', 'payCardSex', 'payCardBirthDate', 'corridorName',
                'tapInTime', 'tapOutTime', 'payAmount', 'Age', 'AgeGroup',
                'TravelDuration', 'TapInHour', 'TimeCategory', 'PeakHour',
                'DayName', 'DayType', 'StopsPassed', 'TravelType', 'Cluster'
            ];
            
            foreach ($requiredHeaders as $req) {
                if (!in_array($req, $header)) {
                    throw new Exception("Header CSV tidak valid! Kolom '{$req}' tidak ditemukan.");
                }
            }

            $headerMap = array_flip($header);

            // Hitung total baris
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key();
            $file->rewind();
            $file->fgetcsv(); // skip header

            $progressBar = $this->command->getOutput()->createProgressBar($totalLines);
            $progressBar->start();
            $startTime = microtime(true);

            $chunkSize = 500;
            $batchData = [];
            $importedRows = 0;
            $skippedRows = 0;
            $now = Carbon::now();

            // Idempotent approach tanpa upsert (karena trans_id sudah hilang di CSV)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('transjakarta_trips')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::transaction(function () use ($file, $headerMap, $pythonToCodeMap, $clusterMaster, $now, &$batchData, $chunkSize, &$importedRows, &$skippedRows, $progressBar) {
                while (!$file->eof()) {
                    $row = $file->fgetcsv();
                    if (empty($row) || count($row) !== count($headerMap)) {
                        $skippedRows++;
                        continue;
                    }

                    $pythonClusterInt = trim($row[$headerMap['Cluster']]);
                    if (!isset($pythonToCodeMap[$pythonClusterInt])) {
                        $skippedRows++;
                        continue;
                    }
                    
                    $clusterCode = $pythonToCodeMap[$pythonClusterInt];
                    $dbClusterId = $clusterMaster[$clusterCode] ?? null;

                    if (!$dbClusterId) {
                        $skippedRows++;
                        continue;
                    }

                    $batchData[] = [
                        'trans_id'            => null, // Konsisten dengan skema unik/nullable
                        'pay_card_bank'       => $row[$headerMap['payCardBank']] ?? null,
                        'pay_card_sex'        => $row[$headerMap['payCardSex']] ?? null,
                        'pay_card_birth_date' => is_numeric($row[$headerMap['payCardBirthDate']]) ? (int)$row[$headerMap['payCardBirthDate']] : null,
                        'corridor_name'       => $row[$headerMap['corridorName']] ?? null,
                        'tap_in_time'         => $row[$headerMap['tapInTime']] ?? null,
                        'tap_out_time'        => $row[$headerMap['tapOutTime']] ?? null,
                        'pay_amount'          => (float)$row[$headerMap['payAmount']],
                        'age'                 => (int)$row[$headerMap['Age']],
                        'age_group'           => $row[$headerMap['AgeGroup']] ?? null,
                        'travel_duration'     => (float)$row[$headerMap['TravelDuration']],
                        'tap_in_hour'         => (int)$row[$headerMap['TapInHour']],
                        'time_category'       => $row[$headerMap['TimeCategory']] ?? null,
                        'peak_hour'           => (int)$row[$headerMap['PeakHour']],
                        'day_name'            => $row[$headerMap['DayName']] ?? null,
                        'day_type'            => $row[$headerMap['DayType']] ?? null,
                        'stops_passed'        => (int)$row[$headerMap['StopsPassed']],
                        'travel_type'         => $row[$headerMap['TravelType']] ?? null,
                        'cluster_id'          => $dbClusterId,
                        'created_at'          => $now,
                        'updated_at'          => $now,
                    ];

                    $importedRows++;
                    $progressBar->advance();

                    if (count($batchData) >= $chunkSize) {
                        DB::table('transjakarta_trips')->insert($batchData);
                        $batchData = [];
                    }
                }

                if (!empty($batchData)) {
                    DB::table('transjakarta_trips')->insert($batchData);
                }
            });

            $progressBar->finish();
            $duration = round(microtime(true) - $startTime, 2);
            
            $this->command->newLine(2);
            $this->command->info("===================================");
            $this->command->info("        CSV IMPORT FINISHED        ");
            $this->command->info("===================================");
            $this->command->info("File       : dfTransjakarta_cluster.csv");
            $this->command->info("Imported   : {$importedRows}");
            $this->command->info("Skipped    : {$skippedRows}");
            $this->command->info("Batch      : {$chunkSize}");
            $this->command->info("Execution  : {$duration} sec");
            $this->command->info("===================================");
            $this->command->newLine();

        } catch (Exception $e) {
            $this->command->newLine();
            $this->command->error("===================================");
            $this->command->error("          IMPORT FAILED            ");
            $this->command->error("===================================");
            $this->command->error("Message : " . $e->getMessage());
            $this->command->error("===================================");
            $this->command->newLine();
        }
    }
}
