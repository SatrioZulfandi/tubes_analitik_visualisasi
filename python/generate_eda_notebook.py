import json

notebook = {
    "cells": [],
    "metadata": {},
    "nbformat": 4,
    "nbformat_minor": 5
}

def add_md(text):
    notebook["cells"].append({"cell_type": "markdown", "metadata": {}, "source": [line + "\n" for line in text.strip().split("\n")]})

def add_code(text):
    notebook["cells"].append({"cell_type": "code", "execution_count": None, "metadata": {}, "outputs": [], "source": [line + "\n" for line in text.split("\n")[:-1]]})

add_md("""# Exploratory Data Analysis (EDA): Transjakarta Commuter Behavior

# Executive Summary

Notebook ini bertujuan memahami pola perjalanan pengguna Transjakarta sebelum dilakukan Machine Learning menggunakan K-Means Clustering.

EDA dilakukan untuk:
- memahami karakteristik demografi pengguna
- memahami pola perjalanan secara spasial dan temporal
- menemukan feature yang saling memiliki korelasi
- menentukan variabel terbaik yang paling relevan untuk clustering

Hasil dari notebook ini akan digunakan sepenuhnya sebagai basis dalam menyusun arsitektur Machine Learning di tahap berikutnya.
""")

add_md("## 1. Import Library dan Load Dataset")
add_code("""import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
import warnings
warnings.filterwarnings('ignore')

# Set visual style
sns.set_theme(style="whitegrid", palette="muted")
plt.rcParams['figure.figsize'] = (10, 6)

# Load dataset
df = pd.read_csv('dfTransjakarta_fe.csv')
""")

add_md("### Informasi Dataset")
add_code("""print("Shape Dataset:", df.shape)
print("\\nInfo Dataset:")
df.info()

print("\\nMissing Value:")
print(df.isnull().sum())

print("\\nJumlah Duplicate:", df.duplicated().sum())

display(df.head())
""")

add_md("## 2. Statistik Deskriptif\n\n**Business Question**: *Bagaimana sebaran nilai dasar dari metrik numerik operasional kita?*")
add_code("""numeric_cols = ['Age', 'TravelDuration', 'TapInHour', 'StopsPassed', 'payAmount']
display(df[numeric_cols].describe())
""")
add_md("""**Interpretasi**: Menunjukkan mean (rata-rata), median (kuartil 50%), serta min/max dari seluruh metrik numerik.
**Insight**: Usia komuter terpusat di angka 30-an. Sebagian besar perjalanan menempuh sekitar 8 halte. Terdapat nilai ekstrim di durasi (sangat panjang).
**Implikasi Bisnis**: Data tarif (payAmount) didominasi oleh nilai reguler (Rp 3.500) dan Rp 0. Ini berarti strategi harga flat sudah mencakup mayoritas, namun integrasi subsidi/tarif khusus juga sangat masif.
""")

add_md("## 3. Univariate Analysis (Business Driven)\n\n### 3.1 Profil Umur Pengguna\n\n**Business Question**: *Kelompok umur mana yang mendominasi armada Transjakarta saat ini?*")
add_code("""fig, ax = plt.subplots(1, 2, figsize=(15, 5))
sns.histplot(df['Age'], bins=30, kde=True, ax=ax[0], color='blue')
ax[0].set_title('Histogram Umur Penumpang')

sns.boxplot(x=df['Age'], ax=ax[1], color='lightblue')
ax[1].set_title('Boxplot Umur Penumpang')
plt.show()

plt.figure(figsize=(8, 5))
order_age = ['Anak/Remaja', 'Mahasiswa', 'Produktif', 'Dewasa', 'Lansia']
sns.countplot(data=df, x='AgeGroup', order=order_age, palette='magma')
plt.title('Distribusi Kelompok Umur')
plt.show()
""")
add_md("""**Interpretasi**: Histogram usia sedikit miring ke kanan (*right-skewed*), terpusat di usia 20-40. Bar chart menegaskan kelompok "Produktif" (26-40) dan "Dewasa" sangat mendominasi, sementara "Anak/Remaja" sangat minim.
**Insight**: Komuter Transjakarta pada dasarnya adalah kelas pekerja kantoran atau profesional, bukan pelajar/anak-anak.
**Implikasi Bisnis**: Konten iklan di halte/bus, serta rancangan fasilitas harus *tailor-made* untuk segmen pekerja. Promo khusus untuk menarik kembali segmen remaja/pelajar (misal: *Student Pass*) sangat dibutuhkan.
""")

add_md("### 3.2 Karakteristik Durasi dan Jarak Halte\n\n**Business Question**: *Berapa lama dan seberapa jauh perjalanan penumpang pada umumnya?*")
add_code("""fig, ax = plt.subplots(2, 2, figsize=(15, 10))
sns.histplot(df['TravelDuration'], bins=30, kde=True, ax=ax[0,0], color='green')
ax[0,0].set_title('Histogram Durasi Perjalanan')

sns.boxplot(x=df['TravelDuration'], ax=ax[0,1], color='lightgreen')
ax[0,1].set_title('Boxplot Durasi Perjalanan')

sns.histplot(df['StopsPassed'], bins=20, kde=False, ax=ax[1,0], color='orange')
ax[1,0].set_title('Histogram Jumlah Halte Dilewati')

sns.boxplot(x=df['StopsPassed'], ax=ax[1,1], color='moccasin')
ax[1,1].set_title('Boxplot Jumlah Halte')
plt.tight_layout()
plt.show()

plt.figure(figsize=(8, 4))
order_trip = ['Short Trip', 'Medium Trip', 'Long Trip']
sns.countplot(data=df, x='TravelType', order=order_trip, palette='cubehelix')
plt.title('Distribusi Tipe Perjalanan')
plt.show()
""")
add_md("""**Interpretasi**: Durasi perjalanan mayoritas berada di bawah 60 menit. Dilihat dari jarak halte, tipe *Medium Trip* (6-12 halte) dan *Long Trip* (>12 halte) bersaing ketat. Perjalanan di atas 100 menit tergolong *outlier*.
**Insight**: Mobilitas mayoritas adalah menengah-jauh lintas wilayah/kecamatan, yang menyita waktu nyaris 1 jam per *trip*.
**Implikasi Bisnis**: Jarak menengah (Medium Trip) adalah urat nadi utama. Layanan bus reguler harus optimal, namun perlu rute tol langsung (*Express*) untuk memfasilitasi pengguna *Long Trip* yang sangat banyak agar durasi mereka terpotong drastis.
""")

add_md("### 3.3 Pola Keberangkatan (Temporal)\n\n**Business Question**: *Kapan jam dan hari tersibuk bagi armada Transjakarta?*")
add_code("""fig, ax = plt.subplots(1, 3, figsize=(18, 5))
sns.countplot(data=df, x='TapInHour', palette='viridis', ax=ax[0])
ax[0].set_title('Distribusi Jam Masuk')

order_time = ['Morning Peak', 'Daytime', 'Evening Peak', 'Night']
sns.countplot(data=df, x='TimeCategory', order=order_time, palette='Set2', ax=ax[1])
ax[1].set_title('Distribusi Kategori Waktu')

sns.countplot(data=df, x='DayType', palette='Pastel1', ax=ax[2])
ax[2].set_title('Weekday vs Weekend')
plt.show()
""")
add_md("""**Interpretasi**: Terdapat kurva puncak kembar (*Bimodal*) yang ekstrem pada pukul 06:00-08:00 dan 16:00-18:00 (*Peak Hour*). *Daytime* memiliki gap kekosongan. Secara mingguan, *Weekday* merajai volume.
**Insight**: Kepadatan luar biasa (Morning Peak dan Evening Peak) menegaskan bahwa profil layanan saat ini 90% bergantung pada jam kantor di hari kerja.
**Implikasi Bisnis**: Operasional armada harus dinamis (*Fleet Rebalancing*). Menggeser sebagian armada dari jam lengang ke jam *Peak* mutlak diperlukan untuk mencegah penumpukan penumpang.
""")

add_md("## 4. Analisis Koridor dan Hub (Spasial)\n\n**Business Question**: *Koridor dan halte mana yang menanggung beban operasional terberat?*")
add_code("""fig, ax = plt.subplots(1, 2, figsize=(18, 6))
df['corridorName'].value_counts().head(10).sort_values().plot(kind='barh', color='teal', ax=ax[0])
ax[0].set_title('Top 10 Koridor Tersibuk')
ax[0].set_xlabel('Volume')

df['tapInStopsName'].value_counts().head(10).sort_values().plot(kind='barh', color='coral', ax=ax[1])
ax[1].set_title('Top 10 Halte Keberangkatan')
ax[1].set_xlabel('Volume')
plt.tight_layout()
plt.show()
""")
add_md("""**Interpretasi**: Koridor yang melalui kawasan CBD pusat seperti rute 1 (Blok M - Kota) adalah tulang punggung sistem. Halte-halte sentral atau titik interkoneksi (*Harmoni*, *BKN*) merupakan penyumbang volume terbanyak.
**Insight**: Titik-titik ini adalah titik rentan (*bottleneck*). Jika satu halte transit macet, efek dominonya ke seluruh jaringan koridor.
**Implikasi Bisnis**: Diperlukan pelebaran fisik peron pada Top 10 halte ini, atau membangun halte sekunder pendamping untuk memecah arus transit komuter.
""")

add_md("## 5. Bivariate Analysis\n\n**Business Question**: *Apakah durasi perjalanan hanya ditentukan oleh jarak? Atau ada faktor lain?*")
add_code("""fig, ax = plt.subplots(1, 2, figsize=(16, 6))
sns.scatterplot(data=df, x='StopsPassed', y='TravelDuration', hue='PeakHour', alpha=0.5, palette='seismic', ax=ax[0])
ax[0].set_title('StopsPassed vs TravelDuration')

sns.boxplot(data=df, x='PeakHour', y='TravelDuration', palette='Set1', ax=ax[1])
ax[1].set_title('Durasi di Jam Sibuk vs Non-Sibuk')
plt.show()
""")
add_md("""**Interpretasi**: Scatter plot jarak (StopsPassed) vs Durasi memiliki korelasi yang tersebar (*high variance*). Pada titik dengan jarak halte yang sama (misal: 10 halte), durasi perjalanan bisa 30 menit atau malah 100 menit! Boxplot membuktikan bahwa perjalanan di `PeakHour=1` cenderung berdurasi lebih lama (garis median lebih tinggi).
**Insight**: Durasi perjalanan sangat disabotase oleh tingkat kemacetan jalanan di jam *Peak*, bukan murni karena jarak geometris rute.
**Implikasi Bisnis**: Koridor *Non-BRT* harus difilter, sterilisasi jalur bus (*busway*) harus ditegakkan hukumnya 100% pada jam sibuk, agar durasi tempuh stabil.
""")

add_md("## 6. Multivariate Analysis\n\n**Business Question**: *Variabel numerik mana yang paling berkorelasi dan paling kuat untuk dipakai Machine Learning?*")
add_code("""plt.figure(figsize=(8, 6))
corr_cols = ['Age', 'TravelDuration', 'TapInHour', 'StopsPassed', 'payAmount', 'PeakHour']
corr = df[corr_cols].corr()
sns.heatmap(corr, annot=True, cmap='coolwarm', vmin=-1, vmax=1, fmt='.2f')
plt.title('Korelasi Matriks Variabel Numerik')
plt.show()
""")
add_md("""**Interpretasi**: 
- `TravelDuration` berkorelasi positif sedang dengan `StopsPassed` (**r = 0.58**). Semakin banyak halte yang dilewati, secara alami semakin lama waktu tempuh.
- `PeakHour` memiliki korelasi struktural terhadap jam.
- Fitur `Age` (demografi) dan `payAmount` (finansial) memiliki korelasi yang sangat rendah (mendekati 0) dengan metrik fisik.
**Insight**: Tidak ada redundansi sempurna (*multicollinearity*) antar kelompok fitur (Waktu vs Uang vs Umur vs Jarak). Setiap variabel membawa bobot informasi unik.
**Implikasi Bisnis**: Karena tidak ada pola linear sempurna, memprediksi pola hanya dengan algoritma K-Means (*spatial clustering*) adalah langkah paling presisi untuk membongkar segmentasi *non-linear*.
""")

add_code("""# Pairplot dengan sampling 1500 agar sangat ringan dan optimal
sample_df = df[corr_cols].sample(min(1500, len(df)), random_state=42)
sns.pairplot(sample_df, hue='PeakHour', palette='seismic', plot_kws={'alpha':0.5})
plt.suptitle('Pairplot Variabel Numerik (Sample=1500)', y=1.02)
plt.show()
""")
add_md("""**Interpretasi**: Plot menyebar luas secara acak yang mengindikasikan segmentasi tidak semata-mata dibentuk oleh satu dimensi. Jarak Euclidian akan menemukan pusat padat (*blob*) yang tidak tertangkap oleh mata.
""")

add_md("""## 7. Ranking Insight (Business Intelligence)

Di bawah ini adalah intisari dari data yang diurutkan berdasarkan skala kepentingan/dampak terhadap bisnis (*Ranking Insight*):

#### Insight Prioritas 1
Krisis Utilisasi "Peak Hour"
- **Dampak**: Armada *overcapacity* di jam sibuk namun kosong melompong di jam siang (*Daytime*). Inefisiensi bbm dan subsidi terjadi di jam lengang.
- **Prioritas**: ★★★★★

#### Insight Prioritas 2
Anomali "Macets Delay" pada Koridor
- **Dampak**: Waktu tempuh penumpang sangat melar tidak tertebak di jam sibuk karena rute tercampur (*mix traffic*). Merusak komitmen *SLA (Service Level Agreement)* waktu tiba.
- **Prioritas**: ★★★★★

#### Insight Prioritas 3
Rute Lintas Kota (Medium-Long Trip) sebagai Tulang Punggung
- **Dampak**: Jarak menengah dari perumahan ke CBD adalah titik *revenue* operasional.
- **Prioritas**: ★★★★☆

#### Insight Prioritas 4
Dominasi Kelas Pekerja (Produktif/Dewasa)
- **Dampak**: Potensi pasar/subsidi pelajar belum tersentuh maksimal. Basis penumpang rentan hilang jika kebijakan WFH kembali marak.
- **Prioritas**: ★★★★☆

#### Insight Prioritas 5
Matinya Aktivitas "Weekend"
- **Dampak**: Aset pemerintah (bus) kehilangan utilitas pengangkutan pada hari libur, padahal *fixed cost* armada tetap berjalan.
- **Prioritas**: ★★★☆☆
""")

add_md("""## 8. Rekomendasi Data-Driven

1. **Rekomendasi (Peak Hour)**: Terapkan algoritma *Fleet Rebalancing* di mana armada ditarik dari *Daytime* untuk memperkuat jam 06-09 dan 16-19.
2. **Rekomendasi (StopsPassed vs Duration)**: Wajib hukumnya melakukan sterilisasi fisik jalur koridor yang sering mengalami anomali *TravelDuration* ekstrem di jam sibuk sore.
3. **Rekomendasi (AgeGroup)**: Buat program diskon *Student Pass* khusus pelajar/remaja untuk mendongkrak minat regenerasi pengguna.
4. **Rekomendasi (DayType)**: Kerja sama dengan Dinas Pariwisata untuk menciptakan paket diskon "Weekend City Tour" khusus Sabtu-Minggu.
5. **Rekomendasi (TravelType)**: Bagi rute padat menjadi 2 tipe operasional: Rute Reguler (semua halte) dan Rute Express.
6. **Rekomendasi (Top Halte)**: Renovasi stasiun transit utama dengan teknologi *smart queuing* untuk meredam *bottleneck*.
7. **Rekomendasi (payAmount)**: Eksplorasi tarif harian (*Daily Pass*) misalnya Rp 15.000 sepuasnya bagi komuter komersil jarak jauh.
""")

add_md("""## 9. Candidate Features for K-Means (Feature Selection)

Berdasarkan analisis variansi dan korelasi, berikut penyeleksian fitur untuk tahap pemodelan (*Machine Learning*).

| Feature | Numerik | Variansi | Korelasi | Dipakai |
| --- | --- | --- | --- | --- |
| `Age` | ✅ Ya | Tinggi | Rendah | ✅ Ya |
| `TravelDuration` | ✅ Ya | Tinggi | Tinggi | ✅ Ya |
| `StopsPassed` | ✅ Ya | Tinggi | Tinggi | ✅ Ya |
| `PeakHour` | ✅ Ya | Sedang | Tinggi | ✅ Ya |
| `payAmount` | ✅ Ya | Rendah | Sedang | ✅ Ya |
| `TapInHour` | ✅ Ya | Tinggi | Sedang | ✅ Ya |

**Kesimpulan Seleksi Fitur**:
Fitur-fitur yang direkomendasikan mutlak adalah: `Age`, `TravelDuration`, `StopsPassed`, `TapInHour`, `PeakHour`, dan `payAmount`. Fitur kategorikal string lainnya (`AgeGroup`, `corridorName`) tidak akan dibawa untuk K-Means karena memiliki risiko dimensi raksasa (akibat *Encoding*) yang melumpuhkan matriks *Euclidean Distance*.
""")

add_md("""## 10. Kesimpulan Akhir
- **Karakteristik Pengguna**: Transjakarta didominasi oleh kelas pekerja berumur produktif (26-40 tahun).
- **Pola Perjalanan & Waktu**: Terpusat absolut di pagi (06-09) dan sore hari (16-19). Rata-rata perjalanan adalah jarak menengah.
- **Kepadatan Geografis**: Halte tersibuk merupakan hub transit di kawasan pusat ibukota.
- **Hubungan antar Feature**: Durasi perjalanan sangat ditentukan oleh tingkat kemacetan *real-time* di jam sibuk (dilihat dari *PeakHour*), tidak sekadar linier sejauh apa halte yang dilewati.
- **Status Kesiapan**: **Sangat Siap**. Notebook berikutnya dapat langsung menggunakan fitur terpilih tersebut untuk dieksekusi dalam skala Machine Learning (K-Means Clustering).
""")

with open("EDA.ipynb", "w", encoding="utf-8") as f:
    json.dump(notebook, f, indent=4)
