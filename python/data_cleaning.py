import pandas as pd
import numpy as np

def clean_data(file_path):
    print("Memulai proses Data Cleaning...")
    
    # Load Data
    df = pd.read_csv(file_path)
    print(f"Data Awal: {df.shape[0]} baris, {df.shape[1]} kolom")

    # ==========================================
    # 1. MISSING VALUE
    # ==========================================
    df_clean = df.dropna().copy()
    print("Missing value telah ditangani (Drop Row).")

    # ==========================================
    # 2. DUPLICATE
    # ==========================================
    df_clean = df_clean.drop_duplicates()
    print("Pengecekan duplikat selesai.")

    # ==========================================
    # 6. SUMMARY & EXPORT
    # ==========================================
    print(f"Data Akhir: {df_clean.shape[0]} baris, {df_clean.shape[1]} kolom")
    
    # Menyimpan hasil cleaning ke dataset baru
    output_path = file_path.replace('.csv', '_clean.csv')
    df_clean.to_csv(output_path, index=False)
    print(f"Dataset bersih telah disimpan ke: {output_path}")

if __name__ == "__main__":
    clean_data('dfTransjakarta.csv')
