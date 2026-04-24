# Tabel Unit Testing Sistem SportLend

| No. | ID Tes | Modul | Skenario Pengujian | Langkah-langkah | Data Uji (Jika Perlu) | Hasil yang Diharapkan | Hasil Aktual (Lulus/Gagal) |
| --- | --- | --- | --- | --- | --- | --- | --- |
| 1 | AUTH001 | Autentikasi | Akses halaman admin tanpa login | Logout lalu akses `/admin/dashboard` | - | Redirect ke `/login` | - |
| 2 | AUTH002 | Autentikasi | Login gagal (password salah) | Buka `/login` lalu submit kredensial salah | `admin@example.com / SalahPassword` | Muncul pesan error login | - |
| 3 | AUTH003 | Autentikasi | Login sukses admin | Login dengan akun role `admin` | `admin@example.com / Admin123` | Redirect ke `/admin/dashboard` | - |
| 4 | AUTH004 | Autentikasi | Login sukses peminjam | Login dengan akun role `peminjam` | `user1@example.com / User123` | Redirect ke `/peminjam/dashboard` | - |
| 5 | AUTH005 | Autentikasi | Register peminjam sukses | Buka `/register`, isi form role `peminjam`, submit | Nama, email unik, password valid | Akun tersimpan dan auto-login ke dashboard peminjam | - |
| 6 | AUTH006 | Autentikasi | Register admin gagal (kode rahasia salah) | Buka `/register`, pilih role `admin`, isi kode rahasia salah, submit | `admin_secret_code` tidak sesuai config | Validasi gagal, pesan kode rahasia tidak valid | - |
| 7 | AUTH007 | Autentikasi | Register admin sukses | Buka `/register`, pilih role `admin`, isi kode rahasia benar, submit | Data valid + `admin_secret_code` benar | Akun admin tersimpan dan auto-login ke dashboard admin | - |
| 8 | AUTH008 | Autentikasi | Logout sukses | Klik tombol logout | - | Session berakhir dan redirect ke `/login` | - |
| 9 | USER001 | Kelola User (Admin) | Lihat daftar user | Login admin lalu akses `/admin/users` | - | Tabel user tampil | - |
| 10 | USER002 | Kelola User (Admin) | Tambah user baru | Buka form tambah user lalu submit data valid | Nama, email unik, password valid, role | User berhasil tersimpan | - |
| 11 | USER003 | Kelola User (Admin) | Tambah user dengan email duplikat | Submit form tambah user dengan email yang sudah ada | `admin@example.com` | Validasi gagal (email sudah dipakai) | - |
| 12 | USER004 | Kelola User (Admin) | Edit data user | Buka edit user lalu ubah nama/email/role | Data valid | Data user terupdate | - |
| 13 | USER005 | Kelola User (Admin) | Edit password user | Buka edit user lalu isi password baru valid | Password 5-20 karakter sesuai regex | Password user berhasil berubah | - |
| 14 | USER006 | Kelola User (Admin) | Hapus akun sendiri ditolak | Login admin, klik hapus pada akun sendiri | - | Muncul error "Tidak bisa menghapus user sendiri." | - |
| 15 | KAT001 | Kategori (Admin) | Lihat daftar kategori | Akses `/admin/categories` | - | Tabel kategori tampil | - |
| 16 | KAT002 | Kategori (Admin) | Tambah kategori baru | Isi form tambah kategori dengan nama unik | Nama kategori baru | Kategori berhasil tersimpan | - |
| 17 | KAT003 | Kategori (Admin) | Tambah kategori duplikat | Isi nama kategori yang sudah ada | Nama kategori existing | Validasi gagal (nama sudah digunakan) | - |
| 18 | KAT004 | Kategori (Admin) | Edit kategori | Ubah nama/deskripsi kategori lalu simpan | Data valid | Kategori terupdate | - |
| 19 | KAT005 | Kategori (Admin) | Hapus kategori | Klik hapus kategori | - | Kategori terhapus | - |
| 20 | ALT001 | Alat/Buku (Admin) | Lihat daftar alat/buku | Akses `/admin/tools` | - | Tabel alat/buku tampil | - |
| 21 | ALT002 | Alat/Buku (Admin) | Tambah alat/buku baru | Isi form tambah dengan data valid | Kode unik, nama, qty, kondisi | Data alat/buku tersimpan, `available = quantity` | - |
| 22 | ALT003 | Alat/Buku (Admin) | Upload cover valid | Tambah/edit alat dengan file JPG/PNG <= 2MB | `cover_image` valid | Cover berhasil tersimpan | - |
| 23 | ALT004 | Alat/Buku (Admin) | Upload cover tidak valid | Upload file non-gambar/lebih 2MB | PDF atau file >2MB | Validasi gagal pada `cover_image` | - |
| 24 | ALT005 | Alat/Buku (Admin) | Tambah kode duplikat | Submit form dengan `code` yang sudah ada | Kode existing | Validasi gagal (kode unik) | - |
| 25 | ALT006 | Alat/Buku (Admin) | Edit alat/buku | Ubah data alat lalu simpan | Data valid | Data alat/buku terupdate | - |
| 26 | ALT007 | Alat/Buku (Admin) | Hapus alat/buku | Klik hapus data alat | - | Data alat/buku terhapus | - |
| 27 | PMN001 | Jelajah Alat (Peminjam) | Lihat alat tersedia | Login peminjam lalu akses `/peminjam/tools` | - | Daftar alat dengan `available > 0` tampil | - |
| 28 | PMN002 | Jelajah Alat (Peminjam) | Detail alat | Klik detail salah satu alat | - | Halaman detail alat tampil | - |
| 29 | PMN003 | Jelajah Alat (Peminjam) | Pencarian alat | Gunakan pencarian/filter alat | `name`, `category_id`, `condition` | Hasil sesuai filter tampil | - |
| 30 | BRW001 | Peminjaman (Peminjam) | Lihat riwayat peminjaman sendiri | Akses `/peminjam/borrowings` | - | Daftar peminjaman milik user login tampil | - |
| 31 | BRW002 | Peminjaman (Peminjam) | Ajukan peminjaman sukses | Isi form `/peminjam/borrowings/create` lalu submit | Tool tersedia, qty valid, tanggal valid | Data tersimpan dengan status `pending` | - |
| 32 | BRW003 | Peminjaman (Peminjam) | Ajukan dengan qty melebihi stok | Submit form dengan `quantity > available` | Qty lebih besar dari stok | Gagal simpan, muncul error jumlah tidak tersedia | - |
| 33 | BRW004 | Peminjaman (Peminjam) | Ajukan dengan due_date tidak valid | Isi `due_date <= borrow_date` | Tanggal tidak valid | Validasi gagal pada `due_date` | - |
| 34 | BRW005 | Peminjaman (Peminjam) | Batalkan peminjaman pending | Klik batalkan pada peminjaman status `pending` | - | Status berubah menjadi `rejected` | - |
| 35 | BRW006 | Peminjaman (Peminjam) | Batalkan peminjaman non-pending ditolak | Coba batalkan status `approved/returned/rejected` | - | Muncul error tidak bisa dibatalkan | - |
| 36 | BRW007 | Peminjaman (Peminjam) | Lihat detail peminjaman user lain ditolak | Akses `/peminjam/borrowings/{id_user_lain}` | ID peminjaman milik user lain | Response 403 | - |
| 37 | BRWA001 | Peminjaman (Admin) | Lihat daftar peminjaman | Akses `/admin/borrowings` | - | Daftar peminjaman tampil | - |
| 38 | BRWA002 | Peminjaman (Admin) | Buat peminjaman status pending | Admin buat peminjaman dari form create | Data valid + status `pending` | Peminjaman tersimpan, stok tidak berkurang | - |
| 39 | BRWA003 | Peminjaman (Admin) | Buat peminjaman status approved | Admin buat peminjaman status `approved` | Stok cukup | Peminjaman tersimpan, stok `available` berkurang | - |
| 40 | BRWA004 | Peminjaman (Admin) | Approved saat stok tidak cukup | Admin set status `approved` dengan qty > stok | Stok kurang | Gagal proses (422, stok tidak mencukupi) | - |
| 41 | BRWA005 | Peminjaman (Admin) | Ubah status peminjaman | Ubah status via endpoint status | pending -> approved / approved -> rejected | Status berubah dan stok disesuaikan otomatis | - |
| 42 | BRWA006 | Peminjaman (Admin) | Hapus peminjaman allowed | Hapus peminjaman status `pending/rejected` | - | Data peminjaman terhapus | - |
| 43 | BRWA007 | Peminjaman (Admin) | Hapus peminjaman non-allowed | Hapus peminjaman status `approved/returned` | - | Ditolak dengan pesan error | - |
| 44 | RET001 | Pengembalian (Peminjam) | Lihat daftar yang bisa dikembalikan | Akses `/peminjam/returns` | - | Hanya peminjaman status `approved` milik user tampil | - |
| 45 | RET002 | Pengembalian (Peminjam) | Submit pengembalian sukses | Submit `/peminjam/returns/{borrowing}` | Qty valid, kondisi valid | Data return tersimpan, stok bertambah, fine dihitung otomatis | - |
| 46 | RET003 | Pengembalian (Peminjam) | Submit pengembalian duplikat | Submit ulang untuk borrowing yang sudah punya return | Borrowing sama | Ditolak dengan pesan sudah memiliki pengembalian | - |
| 47 | RET004 | Pengembalian (Peminjam) | Submit qty melebihi pinjaman | Isi `quantity_returned` > `borrowing->quantity` | Qty invalid | Validasi gagal | - |
| 48 | RET005 | Pengembalian (Peminjam) | Akses form return milik user lain ditolak | Akses `/peminjam/returns/{id_user_lain}` | ID borrowing user lain | Response 403 | - |
| 49 | RETA001 | Pengembalian (Admin) | Lihat daftar pengembalian | Akses `/admin/returns` | - | Daftar pengembalian tampil | - |
| 50 | RETA002 | Pengembalian (Admin) | Tambah pengembalian untuk borrowing approved | Submit form create return admin | Borrowing approved tanpa return | Return tersimpan, stok bertambah, fine dibuat/update | - |
| 51 | RETA003 | Pengembalian (Admin) | Tambah pengembalian untuk borrowing non-approved | Submit return untuk borrowing bukan approved | Status bukan `approved` | Ditolak (422) | - |
| 52 | RETA004 | Pengembalian (Admin) | Edit pengembalian | Ubah qty/kondisi return lalu simpan | Data valid | Return dan fine dihitung ulang, status borrowing menyesuaikan | - |
| 53 | RETA005 | Pengembalian (Admin) | Hapus pengembalian | Hapus return yang ada | - | Return terhapus, stok/status borrowing rollback, fine disesuaikan | - |
| 54 | FINE001 | Denda (Admin) | Lihat daftar denda | Akses `/admin/fines` | - | Daftar denda tampil | - |
| 55 | FINE002 | Denda (Admin) | Lihat detail denda | Akses `/admin/fines/{id}` | ID fine valid | Detail denda tampil | - |
| 56 | FINE003 | Denda (Admin) | Tandai denda lunas | Aksi `mark-paid` pada denda | Opsional catatan admin | Status berubah jadi `paid`, `paid_at` terisi | - |
| 57 | FINE004 | Denda (Admin) | Bebaskan denda tanpa catatan ditolak | Aksi `waive` tanpa `notes_admin` | notes kosong | Validasi gagal | - |
| 58 | FINE005 | Denda (Admin) | Bebaskan denda dengan catatan | Aksi `waive` dengan catatan | notes valid | Status berubah jadi `waived`, `paid_at` null | - |
| 59 | FSET001 | Pengaturan Denda (Admin) | Lihat pengaturan tarif denda | Akses `/admin/fines-settings` | - | Form pengaturan tarif tampil | - |
| 60 | FSET002 | Pengaturan Denda (Admin) | Update pengaturan tarif denda | Submit nilai tarif baru | Nilai integer >= 0 | Pengaturan tersimpan | - |
| 61 | STS001 | Status (Admin) | Lihat tab status peminjaman | Akses `/admin/status?tab=peminjaman` | - | Data status peminjaman tampil | - |
| 62 | STS002 | Status (Admin) | Lihat tab status pengembalian | Akses `/admin/status?tab=pengembalian` | - | Data status pengembalian tampil | - |
| 63 | DASH001 | Dashboard | Dashboard admin | Login admin lalu akses `/admin/dashboard` | - | Statistik admin tampil | - |
| 64 | DASH002 | Dashboard | Dashboard peminjam | Login peminjam lalu akses `/peminjam/dashboard` | - | Statistik peminjam tampil | - |

