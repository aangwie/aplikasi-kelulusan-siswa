---
# Aplikasi Kelulusan Siswa

Aplikasi Kelulusan Siswa adalah sebuah sistem sederhana untuk mengelola dan menampilkan informasi kelulusan siswa. Aplikasi ini dirancang agar mudah digunakan dan dapat disesuaikan dengan konfigurasi database Anda.

---

## Fitur

* **Pembaruan Status Kelulusan:** Memungkinkan pembaruan status kelulusan siswa secara mudah.
* **Tampilan Informasi Siswa:** Menampilkan detail informasi siswa beserta status kelulusannya.
* **Konfigurasi Database Fleksibel:** Pengaturan koneksi database dapat diubah melalui file `config.php`.

---

## Persyaratan Sistem

Sebelum menjalankan aplikasi ini, pastikan Anda memiliki:

* **Web Server:** Apache, Nginx, atau lainnya.
* **PHP:** Versi 8.2 atau lebih baru.
* **Database:** MySQL atau MariaDB.

---

## Instalasi

Ikuti langkah-langkah di bawah ini untuk menginstal Aplikasi Kelulusan Siswa:

1.  **Clone Repositori:**
    ```bash
    git clone https://github.com/aangwie/aplikasi-kelulusan-siswa.git
    ```
    (Ganti `nama_user_anda` dengan username GitHub Anda jika Anda sudah mem-fork atau mengunduh langsung repositori ini.)

2.  **Pindahkan ke Direktori Web Server:**
    Pindahkan folder `aplikasi-kelulusan-siswa` ke direktori root web server Anda (misalnya, `htdocs` untuk XAMPP, `www` untuk WAMP, atau `/var/www/html` untuk Linux).

3.  **Buat Database:**
    Buat database baru di MySQL/MariaDB Anda. Anda bisa menamakannya `db_kelulusan` atau sesuai keinginan Anda.

4.  **Import Struktur Database:**
    Import file SQL yang disediakan (misalnya, `database.sql` jika ada) ke database yang baru Anda buat. Jika tidak ada file SQL yang disediakan, Anda mungkin perlu membuat tabel secara manual atau mengikuti instruksi di dokumentasi aplikasi (jika ada).

5.  **Konfigurasi Database:**
    Buka file **`config.php`** yang terletak di root direktori aplikasi. Sesuaikan detail koneksi database dengan konfigurasi Anda:

    ```php
    <?php
    define('DB_HOST', 'localhost'); // Host database Anda
    define('DB_USER', 'root');     // Username database Anda
    define('DB_PASS', '');         // Password database Anda
    define('DB_NAME', 'db_kelulusan'); // Nama database Anda
    ?>
    ```
    Pastikan untuk mengisi `DB_USER`, `DB_PASS`, dan `DB_NAME` sesuai dengan kredensial database Anda.

6.  **Akses Aplikasi:**
    Buka browser Anda dan akses aplikasi melalui URL web server Anda (contoh: `http://localhost/aplikasi-kelulusan-siswa`).

---

## Penggunaan

Setelah instalasi selesai, Anda dapat mulai menggunakan aplikasi untuk mengelola data kelulusan siswa. Navigasi melalui antarmuka pengguna untuk menambahkan, mengedit, atau melihat status kelulusan siswa.

---

## Kontribusi

Kami sangat menghargai kontribusi Anda! Jika Anda memiliki ide atau ingin meningkatkan aplikasi ini, silakan:

1.  Fork repositori ini.
2.  Buat branch baru (`git checkout -b fitur/nama-fitur-baru`).
3.  Lakukan perubahan Anda.
4.  Commit perubahan Anda (`git commit -m 'Tambahkan fitur baru'`).
5.  Push ke branch (`git push origin fitur/nama-fitur-baru`).
6.  Buka Pull Request.

---

## Lisensi

Aplikasi ini dirilis di bawah lisensi MIT. Lihat file `LICENSE` untuk informasi lebih lanjut.

---
