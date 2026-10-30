# Panduan kerja tim DeSanur

## Git workflow
- `main`: versi stabil.
- `develop`: integrasi fitur.
- `feature/<nama-fitur>`: pekerjaan masing-masing anggota.
- Buat Pull Request dari feature branch ke `develop`.
- Merge ke `main` hanya setelah integrasi dan pengujian.

## Aturan kolaborasi
1. Tarik perubahan terbaru dari `develop` sebelum mulai.
2. Jangan commit file `.env`, `vendor/`, atau `node_modules/`.
3. Koordinasikan perubahan migration dan relasi database.
4. Hindari mengubah file bersama tanpa komunikasi.
5. Setiap fitur harus diuji sebelum Pull Request.
6. Tentukan satu integrator untuk review dan merge.

## Pembagian modul contoh
- Authentication dan profil.
- Explore, pencarian, detail destinasi.
- Rekomendasi, Trip Planner, favorit, itinerary, review.
- Admin dashboard, pengelolaan data, moderasi review.

Sesuaikan pembagian dengan jumlah anggota tim.
