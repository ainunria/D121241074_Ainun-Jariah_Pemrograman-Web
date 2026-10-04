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

---

### 3.3 Second Normal Form (2NF)

Syarat 2NF: sudah 1NF dan **tidak ada ketergantungan parsial**, yaitu atribut non-key harus bergantung pada **seluruh** Primary Key, bukan hanya sebagian.

Dengan Primary Key gabungan **(ID Peminjaman, ID Buku)**, ketergantungan fungsionalnya adalah:

| Ketergantungan | Jenis |
|---|---|
| ID Peminjaman → ID Mahasiswa, NIM, Nama Mahasiswa, Jurusan, Email, Tanggal Pinjam, Batas Kembali | Parsial (hanya bergantung pada ID Peminjaman) |
| ID Buku → Judul Buku, ID Penerbit, Nama Penerbit | Parsial (hanya bergantung pada ID Buku) |
| (ID Peminjaman, ID Buku) → Tanggal Kembali, Status | Penuh (bergantung pada seluruh PK) |

Untuk menghilangkan ketergantungan parsial, tabel dipecah menjadi tiga:

**Transaksi_Peminjaman** (PK: id_peminjaman)

| id_peminjaman | id_mahasiswa | nim | nama_mahasiswa | jurusan | email | tanggal_pinjam | batas_kembali |
|---|---|---|---|---|---|---|---|
| P001 | M01 | 221001 | Ani | Teknik Informatika | ani@kampus.ac.id | 01-10-2026 | 08-10-2026 |

**Buku** (PK: id_buku)

| id_buku | judul_buku | id_penerbit | nama_penerbit |
|---|---|---|---|
| B001 | Basis Data | PB1 | Andi |
| B002 | Pemrograman Python | PB2 | Erlangga |

**Detail_Peminjaman** (PK: id_peminjaman + id_buku)

| id_peminjaman | id_buku | tanggal_kembali | status |
|---|---|---|---|
| P001 | B001 | 05-10-2026 | Dikembalikan |
| P001 | B002 | NULL | Dipinjam |

Pada tahap ini ketergantungan parsial sudah hilang. Namun masih ada ketergantungan antar atribut non-key (lihat 3NF).

---

### 3.4 Third Normal Form (3NF)

Syarat 3NF: sudah 2NF dan **tidak ada ketergantungan transitif**, yaitu atribut non-key tidak boleh bergantung pada atribut non-key lainnya.

Ketergantungan transitif yang masih ada pada hasil 2NF:

- Pada **Transaksi_Peminjaman**:
  `id_peminjaman → id_mahasiswa → nim, nama_mahasiswa, jurusan, email`
  Atribut `nim`, `nama_mahasiswa`, `jurusan`, dan `email` bergantung pada `id_mahasiswa` (non-key), bukan langsung pada `id_peminjaman`.
- Pada **Buku**:
  `id_buku → id_penerbit → nama_penerbit, alamat`
  Atribut `nama_penerbit` dan `alamat` bergantung pada `id_penerbit` (non-key), bukan langsung pada `id_buku`.

Solusinya, atribut yang bergantung transitif dipindahkan ke tabel sendiri, dan tabel lama hanya menyimpan Foreign Key:

| Tabel hasil 3NF | Isi | Foreign Key |
|---|---|---|
| **Mahasiswa** | id_mahasiswa, nim, nama_mahasiswa, jurusan, email | - |
| **Penerbit** | id_penerbit, nama_penerbit, alamat | - |
| **Buku** | id_buku, judul_buku, penulis, tahun_terbit, stok, id_penerbit | id_penerbit → Penerbit |
| **Transaksi_Peminjaman** | id_peminjaman, id_mahasiswa, tanggal_pinjam, batas_kembali | id_mahasiswa → Mahasiswa |
| **Detail_Peminjaman** | id_peminjaman, id_buku, tanggal_kembali, status | id_peminjaman → Transaksi_Peminjaman, id_buku → Buku |

Dengan demikian, setiap atribut non-key hanya bergantung pada Primary Key tabelnya masing-masing. Data mahasiswa dan penerbit cukup disimpan satu kali, lalu direferensikan melalui Foreign Key.

Hasil akhir telah memenuhi bentuk normal hingga **3NF**.