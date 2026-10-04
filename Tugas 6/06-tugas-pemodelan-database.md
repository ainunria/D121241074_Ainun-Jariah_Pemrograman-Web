# Perancangan ERD E-Library Kampus
# D121241074 Ainun Jariah

## 1. Identifikasi Entitas

Berdasarkan skenario sistem E-Library kampus, terdapat beberapa entitas utama yang digunakan untuk menyimpan data mahasiswa, buku, penerbit, serta proses peminjaman.

Entitas yang digunakan:

1. **Mahasiswa** — menyimpan data pengguna perpustakaan.
2. **Penerbit** — menyimpan data penerbit buku.
3. **Buku** — menyimpan informasi buku yang tersedia di perpustakaan.
4. **Transaksi_Peminjaman** — menyimpan informasi utama setiap transaksi peminjaman.
5. **Detail_Peminjaman** — menyimpan daftar buku yang terdapat dalam suatu transaksi peminjaman.

---

## 2. Atribut, Primary Key, dan Foreign Key

### A. Tabel Mahasiswa

| Atribut | Tipe Data | Key | Keterangan |
|---|---|---|---|
| id_mahasiswa | INT | PK | Identitas unik mahasiswa |
| nim | VARCHAR(20) | UNIQUE | Nomor induk mahasiswa |
| nama_mahasiswa | VARCHAR(100) | - | Nama lengkap mahasiswa |
| jurusan | VARCHAR(100) | - | Jurusan mahasiswa |
| email | VARCHAR(100) | - | Email mahasiswa |

### B. Tabel Penerbit

| Atribut | Tipe Data | Key | Keterangan |
|---|---|---|---|
| id_penerbit | INT | PK | Identitas unik penerbit |
| nama_penerbit | VARCHAR(100) | - | Nama penerbit |
| alamat | VARCHAR(200) | - | Alamat penerbit |

### C. Tabel Buku

| Atribut | Tipe Data | Key | Keterangan |
|---|---|---|---|
| id_buku | INT | PK | Identitas unik buku |
| judul_buku | VARCHAR(200) | - | Judul buku |
| penulis | VARCHAR(100) | - | Nama penulis |
| tahun_terbit | YEAR | - | Tahun buku diterbitkan |
| stok | INT | - | Jumlah buku yang tersedia |
| id_penerbit | INT | FK | Mengacu pada tabel Penerbit |