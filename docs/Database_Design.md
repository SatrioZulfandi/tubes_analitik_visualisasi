# Database Design: Transjakarta BI Dashboard

Dokumen ini merupakan rancangan arsitektur basis data (Database Design) resmi untuk aplikasi Laravel Business Intelligence Transjakarta. Desain ini disusun dengan mempertimbangkan prinsip performa kueri analitik (OLAP - *Online Analytical Processing*) dan standar akademis normalisasi.

---

## 1. Identifikasi Entitas

Berdasarkan dataset `dfTransjakarta_cluster.csv` dan kebutuhan fungsional *Dashboard Blueprint*, berikut adalah entitas yang akan dibangun di dalam sistem:

1. **`transjakarta_trips` (Tabel Transaksi Utama / Fact Table)**
   - *Fungsi*: Menyimpan seluruh riwayat log perjalanan penumpang Transjakarta.
   - *Alasan*: Menghindari penamaan yang terlalu umum (`trips`), sehingga jika sistem berkembang untuk menampung data MRT atau LRT (`mrt_trips`, `lrt_trips`), struktur arsitektur tetap jelas dan terpisah.
2. **`cluster_master` (Tabel Referensi)**
   - *Fungsi*: Menyimpan data master (metadata) terkait segmentasi Machine Learning.
   - *Alasan*: Memisahkan nama bisnis dan karakteristik klaster dari tabel utama untuk mencegah redundansi, sekaligus mempertegas posisinya sebagai data master (dimensi).
3. **`insights` (Tabel Modul)**
   - *Fungsi*: Menyimpan daftar temuan analitik (Prioritas, Judul, Deskripsi).
   - *Alasan*: Agar konten halaman *Insight* bersifat dinamis dan dapat dikelola (CRUD) oleh Administrator tanpa harus merombak *source code* HTML/Blade.
4. **`recommendations` (Tabel Modul)**
   - *Fungsi*: Menyimpan daftar rekomendasi operasional bergaya Kanban.
   - *Alasan*: Memberikan fleksibilitas bagi manajemen untuk menambah/mengubah rencana aksi (*Action Plan*).

*(Catatan: Entitas User/Admin dilewati di desain ini untuk berfokus pada arsitektur Data Science, namun fitur bawaan `users` Laravel akan otomatis digunakan untuk otentikasi).*

---

## 2. Analisis Normalisasi

### **UNF (Unnormalized Form)**
Wujud asli dari file *CSV* raw hasil ekspor ML. Semua data menumpuk jadi satu baris raksasa tanpa *Primary Key* khusus (hanya bergantung pada indeks baris Pandas).

### **1NF (First Normal Form)**
Dataset kita sudah memenuhi 1NF karena tidak ada kolom yang bernilai ganda (*multi-valued*). Setiap sel (*cell*) hanya berisi satu nilai (misal: Umur 24, tidak ada Umur "24, 25").

### **2NF (Second Normal Form)**
Mewajibkan ketiadaan dependensi parsial. Karena dataset kita butuh *Primary Key* buatan (`id`) yang berdiri tunggal (bukan *Composite Key*), otomatis seluruh kolom fisik (Umur, Jarak, Tarif) sepenuhnya bergantung pada `id` tersebut. 2NF terpenuhi.

### **3NF (Third Normal Form)**
Mewajibkan ketiadaan dependensi transitif (kolom non-PK bergantung pada kolom non-PK lainnya).
- *Masalah pada CSV*: Kolom `ClusterName` (misal: "Komuter Cepat") bergantung pada kolom `Cluster` (0, 1, 2, 3), BUKAN bergantung pada ID Transaksi. 
- *Solusi 3NF*: Kita **WAJIB memisahkan tabel**. Tabel utama hanya menyimpan `cluster_id` (0,1,2,3), sedangkan atribut klasternya dipindahkan ke tabel baru bernama `cluster_master`.

**Kesimpulan Normalisasi:** 
Untuk keperluan aplikasi BI ini, memecah tabel hingga 3NF pada kolom `Cluster` sangat tepat. Namun, untuk kolom seperti `corridorName` dan `tapInStopsName`, memisahkannya menjadi tabel koridor dan halte (*fully normalized*) **TIDAK DISARANKAN**. 
Mengapa? Karena dashboard BI bersifat OLAP (*Read-Heavy*). Terlalu banyak *JOIN* pada puluhan ribu baris data akan membunuh performa. Kita menggunakan pendekatan **Star Schema** ringan.

---

## 3. Entity Relationship Diagram (ERD)

```text
+-------------------+           +--------------------+
|  cluster_master   |           | transjakarta_trips |
+-------------------+           +--------------------+
| id (PK)           | 1       N | id (PK)            |
| name              |<----------| cluster_id (FK)    |
| description       |           | age                |
+-------------------+           | travel_duration    |
                                | stops_passed       |
                                | tap_in_hour        |
+-------------------+           | peak_hour          |
|     insights      |           | pay_amount         |
+-------------------+           | day_type           |
| id (PK)           |           | corridor_name      |
| priority          |           | tap_in_stops       |
| title             |           | tap_out_stops      |
| description       |           | ...                |
+-------------------+           +--------------------+

+-------------------+
| recommendations   |
+-------------------+
| id (PK)           |
| priority_level    |
| problem           |
| solution          |
| expected_impact   |
+-------------------+
```
*Tabel `insights` dan `recommendations` berdiri mandiri (Standalone) karena mereka difungsikan sebagai CMS (Content Management System) penunjang konten DSS.*

---

## 4. Struktur Tabel

### A. Tabel `transjakarta_trips` (Data Log Utama)
| Nama Field | Tipe Data | Panjang | PK/FK | Nullable | Index | Keterangan |
|---|---|---|---|---|---|---|
| `id` | BIGINT | 20 | PK | No | Ya | Auto-increment ID |
| `age` | INT | - | - | No | Tidak | Umur penumpang |
| `travel_duration` | INT | - | - | No | Tidak | Durasi perjalanan (menit) |
| `stops_passed` | INT | - | - | No | Tidak | Jarak (jumlah halte) |
| `tap_in_hour` | INT | - | - | No | Tidak | Jam masuk (0-23) |
| `pay_amount` | INT | - | - | No | Tidak | Tarif (Rp) |
| `peak_hour` | TINYINT | 1 | - | No | Ya | Biner 1 (Sibuk) atau 0 |
| `day_type` | VARCHAR | 20 | - | No | Ya | 'Weekday' / 'Weekend' |
| `corridor_name` | VARCHAR | 150 | - | No | Ya | Nama koridor/rute |
| `tap_in_stops` | VARCHAR | 150 | - | No | Tidak | Halte keberangkatan |
| `tap_out_stops` | VARCHAR | 150 | - | No | Tidak | Halte tujuan |
| `cluster_id` | INT | - | FK | No | Ya | Foreign key ke cluster_master |

### B. Tabel `cluster_master` (Referensi Segmen ML)
| Nama Field | Tipe Data | Panjang | PK/FK | Nullable | Index | Keterangan |
|---|---|---|---|---|---|---|
| `id` | INT | - | PK | No | Ya | ID (0, 1, 2, 3) |
| `name` | VARCHAR | 100 | - | No | Tidak | Nama bisnis (Pejuang Lintas Zona) |
| `description`| TEXT | - | - | Ya | Tidak | Penjelasan sifat klaster |
| `color_hex` | VARCHAR | 10 | - | Ya | Tidak | Kode warna untuk render UI Chart |

### C. Tabel `insights`
| Nama Field | Tipe Data | Panjang | PK/FK | Nullable | Index | Keterangan |
|---|---|---|---|---|---|---|
| `id` | INT | - | PK | No | Ya | Auto-increment |
| `priority` | TINYINT | 1 | - | No | Ya | 1 (Rendah) s.d 5 (Tinggi) |
| `title` | VARCHAR | 150 | - | No | Tidak | Judul insight |
| `description`| TEXT | - | - | No | Tidak | Bukti dan makna operasional |

### D. Tabel `recommendations`
| Nama Field | Tipe Data | Panjang | PK/FK | Nullable | Index | Keterangan |
|---|---|---|---|---|---|---|
| `id` | INT | - | PK | No | Ya | Auto-increment |
| `priority_level`| VARCHAR| 20 | - | No | Ya | 'High', 'Medium', 'Low' |
| `problem` | VARCHAR | 255 | - | No | Tidak | Masalah yang dipecahkan |
| `solution` | TEXT | - | - | No | Tidak | Rekomendasi/Kanban Action |
| `expected_impact`| VARCHAR| 255 | - | No | Tidak | Target capaian teknis |

---

## 5. Primary Key

Tabel utama `transjakarta_trips` menggunakan **Auto-Increment BIGINT (`id`)** sebagai Primary Key.
*Alasan*: Dataset awal tidak memiliki ID Transaksi asli (identifier tiket telah dihapus saat Data Cleaning). Surrogate Key berbasis angka (*Integer*) sangat ideal karena eksekusinya kilat dalam MySQL dibanding bentuk *String*.

---

## 6. Indexing (Rahasia Kecepatan)

Untuk menjamin **Global Filter** beraksi mulus tanpa jeda (seperti aplikasi *Power BI*), kita meletakkan *Indexing* strategis pada:
- **`cluster_id`**
- **`peak_hour`**
- **`day_type`**
- **`corridor_name`** (Mempercepat query GROUP BY massal untuk visualisasi Top Corridor).

---

## 7. Strategi Relasi

Satu relasi dominan terbentuk dalam sistem:
`transjakarta_trips.cluster_id` ➔ terhubung dengan ➔ `cluster_master.id` (Relasi *Many-to-One* / N:1).
Pemisahan murni ini mengamankan integritas data agar penamaan profil Machine Learning tidak redundan dan mudah diedit secara global.

---

## 8. Strategi Import Data

Dataset hasil Machine Learning (`dfTransjakarta_cluster.csv`) akan diimpor ke database MySQL menggunakan **Laravel Seeder standar**. Seeder akan membaca file CSV kemudian melakukan proses insert ke tabel `transjakarta_trips`.

Pendekatan ini dipilih karena alasan-alasan akademis dan fungsional berikut:
- **Skala Data Ideal**: Ukuran dataset relatif kecil (±31.730 baris) dengan rentang fisik file *CSV* yang hanya berkisar belasan *Megabytes* (MB).
- **Proses Satu Kali (One-Time Setup)**: Proses import hanya dilakukan satu kali pada tahap inisialisasi aplikasi (bukan sistem sinkronisasi dinamis tiap jam).
- **Kesederhanaan Implementasi**: Kode eksekusinya jauh lebih linier, bersih, dan mematuhi pilar (*best-practice*) pembelajaran *Framework* Laravel bagi mahasiswa.
- **Mudah Dipahami dan Dipelihara**: Dosen pembimbing dapat membaca skrip *Seeder* standar tanpa dikeruhkan oleh penambahan fungsi algoritma khusus (*package batch insert* eksotis).
- **RAM Ramah**: Pada spesifikasi laptop standar, proses ini dapat dieksekusi dalam hitungan detik.

*(Teknik Chunking atau Batch Processing yang lebih rumit dikesampingkan karena hal tersebut lebih tepat ditujukan untuk aplikasi Production Level Data-Warehouse berukuran jutaan baris).*

---

## 9. Optimasi Database

1. **Query Performance**: Penggunaan Laravel *Eloquent/Query Builder* khusus fungsi agregat seperti `count()`, `avg()`, dan `sum()` diserahkan sepenuhnya ke mesin database, menghindari penarikan `->get()` ke memori PHP.
2. **Storage**: Penggunaan tipe data spesifik (misal `TINYINT` untuk `peak_hour`) menghemat memori *disk*.

---

## 10. Output Akhir (Kesimpulan)

Desain arsitektur database ini telah direvisi dan disempurnakan:
- **Jumlah Tabel**: 4 Tabel.
- **Nama Tabel**: `transjakarta_trips`, `cluster_master`, `insights`, `recommendations`.
- **Alasan Desain**: Penggunaan skema rasional tanpa membebani sistem dengan pemecahan normalisasi 3NF ekstrem yang akan membunuh *Query BI*. Disertai penamaan tabel modular (antisipasi ekspansi MRT/LRT).
- **Kesiapan Implementasi**: 100% Siap! Spesifikasi arsitektur telah disetujui tanpa residu *error* prosedural. Tim developer kini berhak memulai pemrograman **Laravel Implementation**.
