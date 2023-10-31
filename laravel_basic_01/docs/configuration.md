# Configuration
- Environment variable cocok digunakan untuk jenis konfiugrasi yang memang butuh berubah-ubah nilainya, dan terintegrasi dengan baik dengan environment variable di sistem operasi
- Laravel juga mendukung penulisan konfiugrasi dengan menggunakan PHP Code. Konfiugrasi ini biasanya digunakan ketika memang dibutuhkan tidak terlalu sering berubah, dan biasanya pengaturan hampir sama untuk tiap lokasi dijalankan aplikasi.
- Saat menggunakan fitur laravel configuration, kita juga tetap bisa mengakses environment variable

- Folder Configuration : root\config
- Dan prefix dari konfigurasi diawali dengan file php yang terdapat dalam project tersebut


## Membuat File Konfigurasi
- Buat file konfigurasi
- return konfigurasi dalam bentuk array


## Configuration Cache
- Saat membuat terlalu banyak konfigurasi , akan menjadi masalah saat di prod karena waktu load data yang lama.
- Solusi php artisan config:cache
## Hapus Configuration Cache
- Ketika file cache dibuat, jika kita menambah konfigurasi di config/file config. config tidak bisa diakses karena laravel langsung mengakses cachenya.
- oleh karena itu kita butuh menghapus dan membuat ulang
- php artisan config:clear
