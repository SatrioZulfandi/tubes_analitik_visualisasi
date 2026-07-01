import json

notebook = {
    "cells": [],
    "metadata": {},
    "nbformat": 4,
    "nbformat_minor": 5
}

def add_md(text):
    notebook["cells"].append({
        "cell_type": "markdown",
        "metadata": {},
        "source": [line + "\n" for line in text.strip().split("\n")]
    })

def add_code(text):
    notebook["cells"].append({
        "cell_type": "code",
        "execution_count": None,
        "metadata": {},
        "outputs": [],
        "source": [line + "\n" for line in text.split("\n")[:-1]]
    })

add_md("# Project Besar Analitik dan Visualisasi Data: Feature Engineering")

add_md("## 1. Import Library")
add_code("""import pandas as pd
import numpy as np
import warnings
warnings.filterwarnings('ignore')
""")

add_md("## 2. Load Dataset")
add_code("""# Load dataset
df = pd.read_csv('dfTransjakarta_clean.csv')

# Menyimpan jumlah kolom awal
initial_columns = df.shape[1]

print("Shape Dataset:", df.shape)
display(df.head())
df.info()
print("\\nDataset berhasil dimuat!")
""")

add_md("""## 3. Identifikasi Fitur Baru

Berdasarkan hasil diskusi, kita akan membuat 10 fitur baru:
1. **Age**: Umur saat transaksi.
2. **AgeGroup**: Kategori usia (Mahasiswa, Produktif, Dewasa, Lansia).
3. **TravelDuration**: Durasi perjalanan dalam menit.
4. **TapInHour**: Jam masuk (0-23).
5. **TimeCategory**: Kategori waktu spesifik berdasarkan Peak Hour (Morning Peak, Daytime, Evening Peak, Night).
6. **PeakHour**: Fitur biner (1 untuk jam sibuk, 0 untuk lainnya) khusus untuk clustering.
7. **DayName**: Nama hari dalam Bahasa Indonesia.
8. **DayType**: Weekday atau Weekend.
9. **StopsPassed**: Selisih jarak antar halte.
10. **TravelType**: Kategori perjalanan berdasarkan jumlah halte (Short, Medium, Long Trip).
""")

add_md("## 4. Feature Engineering")

add_md("### 4.1 Membuat Fitur Umur dan Kelompok Umur")
add_code("""# Konversi datetime
df['tapInTime'] = pd.to_datetime(df['tapInTime'])
df['tapOutTime'] = pd.to_datetime(df['tapOutTime'])

# 1. Fitur Umur (Age)
df['Age'] = df['tapInTime'].dt.year - df['payCardBirthDate']

# Validasi Umur
print("Umur < 0:", len(df[df['Age'] < 0]))
print("Umur > 100:", len(df[df['Age'] > 100]))

# 2. Fitur Kelompok Umur (AgeGroup)
def categorize_age(age):
    if age < 18: return 'Anak/Remaja'
    elif 18 <= age <= 25: return 'Mahasiswa'
    elif 26 <= age <= 40: return 'Produktif'
    elif 41 <= age <= 60: return 'Dewasa'
    else: return 'Lansia'

df['AgeGroup'] = df['Age'].apply(categorize_age)
display(df[['payCardBirthDate', 'Age', 'AgeGroup']].head())
""")

add_md("### 4.2 Membuat Fitur Durasi Perjalanan")
add_code("""# 3. Fitur TravelDuration
df['TravelDuration'] = (df['tapOutTime'] - df['tapInTime']).dt.total_seconds() / 60.0

# Validasi nilai negatif dan sangat panjang (> 300 menit / 5 jam)
invalid_durations = df[df['TravelDuration'] < 0]
long_durations = df[df['TravelDuration'] > 300]

print("Durasi Negatif:", len(invalid_durations))
print("Durasi > 300 Menit:", len(long_durations))

# Hapus anomali durasi yang tidak masuk akal (negatif)
if len(invalid_durations) > 0:
    print("Menghapus durasi negatif karena tidak valid secara logika.")
    df = df[df['TravelDuration'] >= 0]
""")

add_md("### 4.3 Membuat Fitur Jam Masuk, TimeCategory, dan PeakHour")
add_code("""# 4. Fitur TapInHour
df['TapInHour'] = df['tapInTime'].dt.hour

# 5. Fitur TimeCategory
def categorize_time(hour):
    if 5 <= hour <= 9: return 'Morning Peak'
    elif 10 <= hour <= 15: return 'Daytime'
    elif 16 <= hour <= 20: return 'Evening Peak'
    else: return 'Night'

df['TimeCategory'] = df['TapInHour'].apply(categorize_time)

# 6. Fitur PeakHour
def is_peak_hour(hour):
    if (6 <= hour <= 9) or (16 <= hour <= 19): return 1
    else: return 0

df['PeakHour'] = df['TapInHour'].apply(is_peak_hour)

display(df[['TapInHour', 'TimeCategory', 'PeakHour']].head(10))
""")

add_md("### 4.4 Membuat Fitur Hari (Bahasa Indonesia) dan Tipe Hari")
add_code("""# 7. Fitur DayName
day_map = {
    'Monday': 'Senin',
    'Tuesday': 'Selasa',
    'Wednesday': 'Rabu',
    'Thursday': 'Kamis',
    'Friday': 'Jumat',
    'Saturday': 'Sabtu',
    'Sunday': 'Minggu'
}
df['DayName'] = df['tapInTime'].dt.day_name().map(day_map)

# 8. Fitur DayType
df['DayType'] = df['DayName'].apply(lambda x: 'Weekend' if x in ['Sabtu', 'Minggu'] else 'Weekday')

display(df[['tapInTime', 'DayName', 'DayType']].head())
""")

add_md("### 4.5 Membuat Fitur Jumlah Halte dan Tipe Perjalanan")
add_code("""# 9. Fitur StopsPassed
df['StopsPassed'] = df['stopEndSeq'] - df['stopStartSeq']

# Investigasi nilai negatif
neg_stops = df[df['StopsPassed'] < 0]
print("Jumlah StopsPassed < 0 (Arah Terbalik/Error):", len(neg_stops))

# Hapus nilai negatif untuk menjaga standar akademik
if len(neg_stops) > 0:
    print("Menghapus baris dengan StopsPassed negatif karena sekuens tidak logis.")
    df = df[df['StopsPassed'] >= 0]

# 10. Fitur TravelType
def categorize_travel(stops):
    if stops <= 5: return 'Short Trip'
    elif 6 <= stops <= 12: return 'Medium Trip'
    else: return 'Long Trip'

df['TravelType'] = df['StopsPassed'].apply(categorize_travel)

display(df[['stopStartSeq', 'stopEndSeq', 'StopsPassed', 'TravelType']].head())
""")

add_md("## 5. Validasi Feature\n\nMemeriksa missing value, nilai negatif, dan konsistensi pada semua fitur baru.")
add_code("""new_features = ['Age', 'AgeGroup', 'TravelDuration', 'TapInHour', 'TimeCategory', 'PeakHour', 'DayName', 'DayType', 'StopsPassed', 'TravelType']

print("---- Missing Values pada Feature Baru ----")
print(df[new_features].isnull().sum())

print("\\n---- Statistik Feature Numerik Baru ----")
display(df[['Age', 'TravelDuration', 'StopsPassed', 'TapInHour']].describe())
""")

add_md("""## 6. Identifikasi Fitur untuk K-Means

| Nama Feature | Numerik / Kategorikal | Layak K-Means | Alasan |
|---|---|---|---|
| `Age` | Numerik | ✅ Ya | Representasi kontinu dari umur. |
| `TravelDuration` | Numerik | ✅ Ya | Waktu perjalanan berskala rasio, ideal untuk clustering. |
| `TapInHour` | Numerik | ✅ Ya | Merepresentasikan sebaran jam sibuk absolut. |
| `StopsPassed` | Numerik | ✅ Ya | Interval jarak halte yang ditempuh. |
| `PeakHour` | Kategorikal (Biner) | ✅ Ya | Biner (0/1) bisa langsung masuk algoritma menghitung kemiripan (meski lebih pas dengan mode spesifik, jarak biner 0 dan 1 dapat ditangani). |
| `TimeCategory` | Kategorikal | ❌ Tidak | Teks String, butuh One-Hot Encoding. |
| `TravelType` | Kategorikal | ❌ Tidak | Ordinal String, butuh Label Encoding (0, 1, 2). |
| `AgeGroup` | Kategorikal | ❌ Tidak | Teks String. |
""")

add_md("""## 7. Identifikasi Fitur yang Tidak Digunakan

Fitur-fitur awal berikut tidak akan dimasukkan ke K-Means:

- `transID`, `payCardID`: **Identifier unik**. Akan menghasilkan distorsi jarak dan noise tanpa pola karena nilainya *pseudo-random*.
- `tapInStopsName`, `tapOutStopsName`, `corridorName`: K-Means tidak memproses string. Jika di-encode, akan memunculkan *Curse of Dimensionality* (dimensi membengkak hingga ratusan kolom) yang melemahkan algoritma jarak.
- `latitude` / `longitude`: Tanpa formula Haversine yang spesifik merubahnya jadi matriks jarak, raw coordinate akan menyebabkan bias spasial yang merusak klaster *behavioral* komuter.
""")

add_md("## 8. Menghapus Fitur Tidak Berguna dan Menyimpan Dataset")
add_code("""# Hapus kolom identifier yang tidak akan pernah dipakai untuk clustering atau sangat berisiko merusak model/EDA ringan
columns_to_drop = ['transID', 'payCardID', 'payCardName', 'tapInStops', 'tapOutStops']
df = df.drop(columns=columns_to_drop)

output_file = 'dfTransjakarta_fe.csv'
df.to_csv(output_file, index=False)
print(f"Dataset berhasil disimpan sebagai {output_file}")
""")

add_md("## 9. Kesimpulan")
add_code("""total_new_features = len(new_features)
total_final_columns = df.shape[1]

print("Feature Engineering Summary")
print("Dataset awal")
print("↓")
print(f"{initial_columns} kolom")
print("↓")
print("Feature baru")
print("↓")
print(f"{total_new_features}")
print("↓")
print("Total")
print("↓")
print(f"{total_final_columns} kolom")
print("↓")
print("Dataset siap digunakan untuk EDA")
""")

with open("Feature_Engineering.ipynb", "w", encoding="utf-8") as f:
    json.dump(notebook, f, indent=4)
