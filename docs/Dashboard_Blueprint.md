# Blueprint: Transjakarta Executive Business Intelligence Dashboard

Dokumen ini merupakan rancangan (blueprint) resmi untuk pengembangan **Executive Dashboard Business Intelligence** berbasis Laravel. 
Dashboard ini tidak hanya berfungsi sebagai media visualisasi, melainkan sebuah **Decision Support System (DSS)** yang dirancang untuk menjawab tiga pertanyaan krusial Direktur Operasional setiap pagi:
1. **Apa yang sedang terjadi?** *(Overview & KPI)*
2. **Mengapa hal itu terjadi?** *(Operational Analysis & Cluster Analysis)*
3. **Apa tindakan yang harus dilakukan?** *(Insight & Recommendation)*

---

## 1. Landing Dashboard (Welcome Page)

Sebelum memasuki kompleksitas data, pengguna akan disambut oleh halaman *Landing* profesional bergaya *splash screen* produk komersial.

**Wireframe:**
```text
+------------------------------------------------------+
| [Logo Transjakarta / Logo Project]                   |
|------------------------------------------------------|
|                                                      |
|       EXECUTIVE BUSINESS INTELLIGENCE DASHBOARD      |
|    Analisis Pola Perjalanan Pengguna Transjakarta    |
|   menggunakan Machine Learning K-Means Clustering    |
|                                                      |
|                [ MASUK DASHBOARD ➔ ]                 |
|                                                      |
+------------------------------------------------------+
```

---

## 2. Tujuan Dashboard & User Persona

**Mengapa dashboard dibuat:**
Menjembatani model analitik kompleks (Machine Learning) dengan pengambil keputusan operasional agar dapat menetapkan kebijakan distribusi armada, tarif, dan jadwal berbasis data (*data-driven*).

**User Persona:**
- **Manajemen/Direktur (Executive)**: Membutuhkan ringkasan instan dan Kanban aksi (Apa yang harus dieksekusi hari ini?).
- **Administrator (Developer)**: Memantau stabilitas sistem dan pembaruan dataset ML.
- **Dosen Penguji**: Mengevaluasi alur *Data Science* end-to-end, kualitas UI/UX, dan kedalaman wawasan bisnis.
- **Mahasiswa/Peneliti**: Eksplorasi dataset mentah, pemahaman *Technology Stack* dan metrik K-Means.

---

## 3. Sitemap (Struktur Menu)

```text
[Landing Page]
 └── Masuk Dashboard
      ├── 🏠 Dashboard (Overview)
      ├── 📊 Cluster Analysis
      ├── 🚌 Operational Analysis
      ├── 💡 Business Insight
      ├── 📌 Recommendation (Kanban)
      ├── 📂 Dataset Explorer
      └── ℹ️ About Project
```

---

## 4. Desain Sidebar & Global Filter (Power BI Style)

**Global Filter Bar**
Terletak statis di bagian paling atas (Header) pada SEMUA halaman. Perubahan pada filter ini akan me-*render* ulang (AJAX) seluruh chart di bawahnya secara *real-time*.
- Filter **Cluster**: [Semua ▼]
- Filter **Hari (DayType)**: [Semua ▼]
- Filter **Jam Sibuk (Peak Hour)**: [Semua ▼]

**Sidebar Kiri**
- **[Logo Project]**
- `---`
- 🏠 **Dashboard**
- 📊 **Cluster Analysis**
- 🚌 **Operational Analysis**
- `---`
- 💡 **Insight**
- 📌 **Recommendation**
- `---`
- 📂 **Dataset**
- ℹ️ **About**

---

## 5. Halaman Utama (Dashboard Overview)

**Welcome Banner:**
```text
+----------------------------------------------------+
| Selamat Datang, Eksekutif.                         |
| Executive Business Intelligence Dashboard          |
| Update Dataset Terakhir: 30 Juni 2026              |
+----------------------------------------------------+
```

**KPI Cards (Dengan Trend & Ikon):**
Kartu metrik tidak boleh kosong, melampirkan konteks sampel dataset.
1. **Total Transaksi**: `31.730` | 🚌 *Ikon Bus* | *Dataset Sample Transjakarta*
2. **Total Cluster**: `4` Segmen | 👥 *Ikon User* | *Identifikasi K-Means*
3. **Volume Peak Hour**: `68%` | 📈 *Trend Naik* | *↑ Sangat Padat*
4. **Rata-rata Umur**: `33 Tahun` | 💼 *Ikon Koper* | *Dominasi Pekerja*
5. **Rata-rata Durasi**: `45 Menit` | ⏱️ *Ikon Jam* | *↑ Terhambat Macet*
6. **Koridor Terpadat**: `Koridor 1` | 🚨 *Ikon Alert* | *Prioritas Optimasi*

**Susunan Visualisasi Overview:**
- *Chart 1*: Fluktuasi Volume Penumpang berdasar Jam (Area Chart).
- *Chart 2*: Distribusi 4 Cluster Utama (Donut Chart).
- *Chart 3*: Top 5 Koridor (Bar Chart).

---

## 6. Halaman Cluster Analysis

Halaman mahakarya yang mendemonstrasikan kekuatan segmentasi secara terstruktur dan naratif.

**Alur Layout Berurutan:**
1. **Pie Chart**: Persentase Distribusi Populasi per Klaster.
2. **Centroid Bar**: Perbandingan absolut fitur (Umur, Jarak, Waktu) antar 4 klaster secara berdampingan.
3. **Radar Chart**: Pola poligon karakteristik tiap klaster (menunjukkan bentuk "DNA" komuter).
4. **Cluster Cards**: 4 Kartu berdampingan berisi ringkasan profil (e.g. *Pejuang Lintas Zona*, *Komuter Cepat*).
5. **Cluster Insight**: Teks konklusi dari interpretasi visualisasi di atas.

---

## 7. Halaman Operational Analysis

Fokus pada metrik fisik transportasi (Mengapa hal itu terjadi?).
- **Peak Hour Analysis**: Bar Chart kemacetan vs kelancaran.
- **Top Corridor & Stops**: 2 Bar Chart vertikal bersandingan.
- **Travel Duration vs Stops**: Scatter Plot/Boxplot membuktikan anomali waktu tempuh.
- **Demografi**: Bar Chart usia penumpang & komposisi *Weekday/Weekend*.

---

## 8. Halaman Insight (Priority Cards)

Menghindari desain list teks membosankan. Tampil menggunakan desain **Priority Card** atraktif.

*Contoh Kartu:*
```text
+--------------------------------------------------+
| ★★★★★ [PRIORITAS TINGGI]                         |
| Kepadatan Ekstrem Peak Hour                      |
| 06.00 - 09.00 dan 16.00 - 19.00                  |
| ------------------------------------------------ |
| Terjadi lonjakan 200% volume penumpang tanpa     |
| diiringi frekuensi armada yang sepadan.          |
+--------------------------------------------------+
```
*(Menampilkan total 15 Kartu Insight bersusun Grid 3-kolom)*

---

## 9. Halaman Recommendation (Kanban Board)

Aksi operasional disusun dalam papan *Kanban* agar Direktur dapat memprioritaskan tugas eksekusi.

**Layout 3 Kolom:**
- **[🔴 High Priority]**
  - Card: Rilis Armada Express Point-to-Point (Dampak: Pangkas Waktu 30%)
  - Card: Sterilisasi Jalur Busway Koridor 1
- **[🟡 Medium Priority]**
  - Card: Diskon Off-Peak Siang Hari Rp 2.000
  - Card: Ekspansi Rute Feeder Microtrans
- **[🟢 Low Priority]**
  - Card: Kerja sama Pariwisata Weekend
  - Card: Program Edukasi Kampus

---

## 10. Halaman Dataset Explorer

Transparansi total dari *Raw Data* hasil pengolahan Python.

**Summary Banner (Di atas Tabel):**
`[ 31.730 Rows ] | [ 27 Columns ] | [ 4 Cluster ML ] | [ 6 Feature K-Means ]`

**Fitur Tabel Interaktif:**
- Global Search Bar.
- Dropdown Filter (Cluster, PeakHour).
- Sorting A-Z.
- Pagination (10, 50, 100).
- Tombol **[Export Excel / CSV]** berwarna Hijau.

---

## 11. About Project & Technology Stack

**Teknologi yang Menggerakkan Dashboard (Tech Stack):**
```text
Python ➔ Pandas ➔ Scikit-Learn ➔ Laravel ➔ MySQL ➔ ApexCharts
```

**Konten Akademis:**
- Tujuan Riset
- Metodologi (Data Cleaning ➔ Feature Eng ➔ EDA ➔ ML)
- Penjelasan Algoritma Euclidean K-Means & StandardScaler.

---

## 12. UI/UX & Rekomendasi Teknologi

- **Library Visualisasi Mutlak**: Menggunakan **ApexCharts**. 
  *(Alasan: Render lebih mulus dari Chart.js, animasi hover modern, responsif tanpa batas, dan integrasi Tooltip multi-series yang sangat memukau secara visual)*.
- **Tema Dashboard**: *Modern Enterprise* bergaya bersih (Latar `#F3F4F6`, Card `#FFFFFF` bersudut *radius* 10px, Aksen warna Biru Tua & Cyan).
- **Responsivitas**: Tampilan otomatis menyusut vertikal di HP (Sidebar terlipat), dan melebar ke formasi *Grid* di layar monitor.

---

## 13. Footer Aplikasi

Di bagian bawah (tersemat di setiap halaman):
```text
Business Intelligence Dashboard - Transjakarta
Developed by [Nama Anda] | NIM: [NIM Anda] | © 2026
```

---
*(Dokumen Blueprint ini telah disetujui dan siap diterjemahkan ke dalam kode pemrograman PHP Laravel & Javascript ApexCharts)*
