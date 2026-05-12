# QRIS Payment Setup - RUMAH CAKE SUPREZZ

## Instruksi Update QRIS Image

Untuk menampilkan QRIS code Anda yang sesuai dengan nama bisnis, silakan:

### Langkah 1: Siapkan File QRIS
- Pastikan Anda memiliki file gambar QRIS dalam format PNG atau JPG
- Ukuran gambar sebaiknya minimal 300x300px
- Nama file QRIS Anda dari screenshot adalah untuk **RUMAH CAKE SUPREZZ** dengan NMID: ID10232733334469

### Langkah 2: Letakkan File di Folder yang Benar
```
Lokasi: c:\xampp\htdocs\poss\public\images\
Nama file: qris_suprezz.png
```

Atau gunakan nama yang berbeda dan update referensi di:
- File: `resources/views/customer/order.blade.php` 
- Cari baris: `<img src="/images/qris_suprezz.png"`
- Sesuaikan nama file jika diperlukan

### Langkah 3: Verifikasi
Buka halaman order online di: `http://localhost/poss/`
- Klik menu > Lanjut Checkout
- Pilih "QRIS" sebagai metode pembayaran
- Klik "Lanjutkan"
- QRIS code Anda harus muncul di modal pembayaran

## Fitur Pembayaran yang Sudah Diimplementasikan

✅ **Pilihan Metode Pembayaran:**
   - **Tunai (Cash)**: Bayar saat ambil pesanan
   - **QRIS**: Scan code dengan e-wallet/m-banking

✅ **Alur Pembayaran:**
   1. Customer pilih menu & checkout
   2. Pilih metode: Tunai atau QRIS
   3. Jika QRIS: Upload bukti transfer
   4. Jika Tunai: Langsung ke form data diri
   5. Kasir verifikasi & proses

✅ **Fitur Database:**
   - Payment model sudah support `payment_method` (qris/cash)
   - Order model tracking payment_method
   - Invoice menampilkan metode pembayaran dengan benar

✅ **Kasir Dashboard:**
   - Walk-in orders menggunakan metode "Tunai" (cash)
   - Invoice menampilkan status pembayaran sesuai metode

---

**Catatan Penting:**
- File QRIS simulasi lama (`qris_simulasi.png`) sudah tidak digunakan
- Gunakan `qris_suprezz.png` dengan informasi bisnis Anda yang sebenarnya
- Pastikan file gambar exists, jika tidak akan error loading
