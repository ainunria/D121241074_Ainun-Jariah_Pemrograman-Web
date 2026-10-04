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

### D. Tabel Transaksi_Peminjaman

| Atribut | Tipe Data | Key | Keterangan |
|---|---|---|---|
| id_peminjaman | INT | PK | Identitas unik transaksi |
| id_mahasiswa | INT | FK | Mahasiswa yang melakukan peminjaman |
| tanggal_pinjam | DATE | - | Tanggal peminjaman |
| batas_kembali | DATE | - | Batas waktu pengembalian |

### E. Tabel Detail_Peminjaman

| Atribut | Tipe Data | Key | Keterangan |
|---|---|---|---|
| id_peminjaman | INT | PK, FK | Mengacu pada transaksi peminjaman |
| id_buku | INT | PK, FK | Mengacu pada buku yang dipinjam |
| tanggal_kembali | DATE (NULL) | - | Tanggal buku dikembalikan; bernilai NULL selama buku belum dikembalikan |
| status | VARCHAR(20) | - | Status buku: `Dipinjam` atau `Dikembalikan` |

Primary Key pada tabel Detail_Peminjaman merupakan gabungan **id_peminjaman** dan **id_buku**.

---

## 3. Normalisasi Data

Normalisasi dilakukan dengan satu contoh kasus yang sama di setiap tahap: mahasiswa Ani meminjam dua buku dalam satu transaksi P001.

### 3.1 Unnormalized Form (UNF)

Pada bentuk awal, seluruh informasi peminjaman dicatat dalam satu tabel. Satu transaksi dapat memiliki lebih dari satu buku, sehingga ada kolom yang berisi banyak nilai (repeating group).

| ID Peminjaman | ID Mahasiswa | NIM | Nama Mahasiswa | Jurusan | Email | ID Buku | Judul Buku | ID Penerbit | Nama Penerbit | Tanggal Pinjam | Batas Kembali | Tanggal Kembali | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| P001 | M01 | 221001 | Ani | Teknik Informatika | ani@kampus.ac.id | B001, B002 | Basis Data, Pemrograman Python | PB1, PB2 | Andi, Erlangga | 01-10-2026 | 08-10-2026 | 05-10-2026, - | Dikembalikan, Dipinjam |

Masalah pada UNF:

- Kolom ID Buku, Judul Buku, Penerbit, Tanggal Kembali, dan Status memuat lebih dari satu nilai.
- Data mahasiswa dan penerbit akan berulang pada setiap transaksi (redundansi).
- Sulit dicari, diubah, dan dihapus apabila jumlah buku dalam satu transaksi bertambah.

---

### 3.2 First Normal Form (1NF)

Syarat 1NF: setiap atribut bernilai atomik (satu kolom hanya menyimpan satu nilai) dan setiap baris dapat diidentifikasi secara unik.

Data dipecah sehingga setiap baris hanya merepresentasikan **satu buku dalam satu transaksi**. Primary Key sementara: **(ID Peminjaman, ID Buku)**.

| ID Peminjaman | ID Mahasiswa | NIM | Nama Mahasiswa | Jurusan | Email | ID Buku | Judul Buku | ID Penerbit | Nama Penerbit | Tanggal Pinjam | Batas Kembali | Tanggal Kembali | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| P001 | M01 | 221001 | Ani | Teknik Informatika | ani@kampus.ac.id | B001 | Basis Data | PB1 | Andi | 01-10-2026 | 08-10-2026 | 05-10-2026 | Dikembalikan |
| P001 | M01 | 221001 | Ani | Teknik Informatika | ani@kampus.ac.id | B002 | Pemrograman Python | PB2 | Erlangga | 01-10-2026 | 08-10-2026 | NULL | Dipinjam |

Seluruh nilai sudah atomik. Namun data mahasiswa, tanggal transaksi, dan data buku masih berulang pada setiap baris.