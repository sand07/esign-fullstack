# Format Excel untuk Import Pertanyaan

## Struktur File Excel

File Excel untuk import pertanyaan harus memiliki struktur kolom sebagai berikut:

| kategori_id | pertanyaan | tipe_jawaban | pilihan_jawaban | is_wajib | urutan | is_active |
|-------------|------------|--------------|-----------------|----------|--------|-----------|

## Penjelasan Kolom:

1. **kategori_id** (Opsional)
   - Nomor ID kategori pertanyaan
   - Kosongkan jika tidak ada kategori
   - Contoh: 1, 2, 3

2. **pertanyaan** (Wajib)
   - Isi pertanyaan survey
   - Contoh: "Bagaimana kepuasan Anda terhadap pelayanan dokter?"

3. **tipe_jawaban** (Wajib)
   - Tipe jawaban yang tersedia
   - Nilai yang valid:
     - `skala` - Rating 1-5
     - `pilihan_ganda` - Pilihan berganda
     - `text` - Text bebas
     - `ya_tidak` - Ya/Tidak

4. **pilihan_jawaban** (Opsional)
   - Hanya untuk `tipe_jawaban = pilihan_ganda`
   - Pisahkan dengan koma
   - Contoh: "Sangat Baik, Baik, Cukup, Kurang"

5. **is_wajib** (Wajib)
   - Apakah pertanyaan wajib dijawab?
   - Nilai: 1 (Ya) atau 0 (Tidak)

6. **urutan** (Wajib)
   - Urutan pertanyaan dalam survey
   - Angka integer (1, 2, 3, dst)

7. **is_active** (Wajib)
   - Status aktif pertanyaan
   - Nilai: 1 (Aktif) atau 0 (Tidak Aktif)

## Contoh Data:

```
kategori_id | pertanyaan                                      | tipe_jawaban   | pilihan_jawaban                    | is_wajib | urutan | is_active
1           | Bagaimana kepuasan pelayanan dokter?            | skala          |                                    | 1        | 1      | 1
1           | Apakah dokter menjelaskan dengan baik?          | ya_tidak       |                                    | 1        | 2      | 1
2           | Bagaimana kondisi ruang tunggu?                 | pilihan_ganda  | Sangat Baik, Baik, Cukup, Kurang   | 1        | 3      | 1
            | Saran untuk perbaikan                           | text           |                                    | 0        | 4      | 1
```

## Cara Download Template:

1. Login ke admin panel
2. Menu **Pertanyaan** > **Template Excel**
3. File template akan otomatis terdownload

## Cara Import:

1. Isi file Excel sesuai format
2. Login ke admin panel
3. Menu **Pertanyaan** > **Import Excel**
4. Upload file Excel
5. Klik **Upload & Import**

## Catatan Penting:

- Baris pertama adalah header (akan diabaikan saat import)
- Pastikan tidak ada baris kosong di tengah data
- Untuk tipe_jawaban `pilihan_ganda`, WAJIB mengisi kolom `pilihan_jawaban`
- Urutan akan menentukan tampilan pertanyaan di form survey
- Jika pilih "Hapus existing", SEMUA pertanyaan lama akan dihapus

## Troubleshooting:

**Q: Import gagal, muncul error "Invalid file format"**
A: Pastikan file berformat .xlsx atau .xls

**Q: Beberapa pertanyaan tidak terimpor**
A: Periksa validasi data (tipe_jawaban harus sesuai, kolom wajib harus diisi)

**Q: Pilihan ganda tidak muncul**
A: Pastikan kolom pilihan_jawaban diisi untuk tipe_jawaban = pilihan_ganda
