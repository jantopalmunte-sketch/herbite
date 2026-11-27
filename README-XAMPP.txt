HERBITE GUMMY - PHP NATIVE + MYSQL (XAMPP)
==========================================

PENTING: Desain asli owner dipertahankan. index.php memakai markup halaman asli,
style.css dan script.js asli tetap disertakan. Fitur transaksi mendapat stylesheet
terpisah di includes/app.css yang memakai warna turunan dari style.css asli.

1. INSTALASI
- Install/jalankan XAMPP.
- Start Apache dan MySQL.
- Ekstrak folder herbitegummyy ke C:\xampp\htdocs\herbitegummyy
- Buka http://localhost/phpmyadmin
- Buat/import database dengan mengimpor database/herbitegummy.sql.
- Periksa config/database.php (default XAMPP: user root, password kosong).
- Buka http://localhost/herbitegummyy/setup_admin.php untuk membuat admin pertama.
- Setelah admin dibuat, HAPUS setup_admin.php dari folder proyek.
- Login melalui http://localhost/herbitegummyy/login.php.

2. PENGATURAN ADMIN WAJIB
Login admin -> Pengaturan QRIS & Produksi:
- Isi alamat produksi yang lengkap.
- Isi latitude dan longitude tempat produksi (koordinat dari Google Maps).
- Upload gambar QRIS milik usaha.
- Tarif default Rp500/km dapat diubah jika owner menghendaki.
Login admin -> Update Stok Harian:
- Isi stok masing-masing varian untuk hari berjalan.
- Varian READY hanya dapat dibeli jika stok hari ini masih tersedia.
- Setiap tanggal baru, admin memasukkan stok baru lagi.

3. ALUR CUSTOMER
- Daftar customer, lalu login.
- Etalase menampilkan 3 rasa; status dan stok berasal dari database.
- Customer pilih rasa yang READY, isi jumlah, nomor HP dan alamat.
- Untuk pengantaran, tekan Gunakan Lokasi Saya. Browser harus diberi izin lokasi.
- Sistem mengambil jarak rute jalan dari layanan OSRM dan menghitung ongkir dengan
  pembulatan ke atas per kilometer x Rp500. Pickup ongkir Rp0.
- Customer membuat pesanan, scan QRIS, lalu upload bukti bayar.
- Admin memverifikasi pembayaran, mengubah status packing, lalu siap pickup/dikirim.
- Customer melihat status, menyimpan nota melalui Cetak -> Save as PDF, lalu konfirmasi
  pesanan diterima dan mengirim testimoni.

4. CATATAN PENTING / YANG PERLU DIKONFIRMASI OWNER
- Nama rasa pada data awal mengikuti sketsa: Mangga (READY), Stroberi dan Anggur
  (COMING SOON). Harga awal Rp10.000 per produk mengikuti sketsa; ubah langsung di
  tabel products jika harga sebenarnya berbeda.
- Foto produk di kartu etalase memakai produkbaru.jpg sementara. Ganti kolom products.image
  dengan nama file foto rasa yang benar bila tersedia.
- Sistem QRIS manual tidak dapat memastikan transfer benar-benar masuk. Admin harus
  mencocokkan bukti dengan mutasi/merchant QRIS sebelum verifikasi.
- Perhitungan rute menggunakan OSRM public routing server; butuh internet dan cURL PHP
  aktif di XAMPP. Untuk produksi/traffic tinggi, gunakan server routing/API berlisensi
  dan kebijakan penggunaan yang sesuai.
- Lokasi GPS browser hanya memberi koordinat; customer tetap mengisi alamat lengkap
  sendiri. Pastikan izin lokasi dan koneksi HTTPS saat website dipasang online.
- Sistem ini belum dikonfigurasi untuk hosting publik, backup otomatis, email OTP,
  notifikasi WhatsApp, atau integrasi payment gateway.

5. KEAMANAN SEBELUM ONLINE
- Ganti password database/admin, gunakan HTTPS, batasi ukuran upload, backup database.
- Pastikan folder uploads tidak dapat mengeksekusi PHP (konfigurasi Apache).
- Hapus setup_admin.php setelah admin pertama dibuat.
- Jangan publikasikan kredensial database.

6. CATATAN FILE ASLI
- Berkas img/logo.png tidak ada di ZIP sumber; HTML asli sudah memiliki fallback
  menyembunyikan gambar logo jika file tidak tersedia, sehingga teks merek tetap tampil.
- Berkas style.css dan script.js asli disalin tanpa mengubah palet/animasi owner, kecuali
  aksi tombol Belanja Sekarang diarahkan ke etalase baru.
