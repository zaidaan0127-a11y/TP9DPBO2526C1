# TP9DPBO2526C1
# MVP Rekrutmen — Kandidat & Lowongan

Aplikasi CRUD berbasis **PHP + MySQL** dengan arsitektur **Model–View–Presenter (MVP)** prinsip **Strict Passive View**.
Terdiri dari dua modul: **Kandidat** (modul awal) dan **Lowongan** (modul tambahan bertema rekrutmen).
____
Janji

Saya Zaidaan Dhyaa Ulhaq Budiono dengan NIM 2500945 mengerjakan Tugas Praktikum 9 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## 1. Desain Program

### 1.1 Arsitektur MVP

```
                 index.php  (composition root + router)
                      |
                      v
 +-----------+   Kontrak    +--------------+   Kontrak    +-----------+
 |   VIEW    | <----------- |  PRESENTER   | -----------> |   MODEL   |
 | (pasif)   |  string/array|  (logika)    |  entity/bool |  (data)   |
 +-----------+              +--------------+              +-----------+
       |                                                        |
   template/*.html                                          MySQL (PDO)
```

| Lapisan | Tanggung jawab | Tidak boleh |
|---|---|---|
| **Model** (`models/`) | Akses database dengan prepared statement, mengubah baris menjadi entity | Mengetahui View / HTML |
| **View** (`views/`) | Mengisi template HTML dari string/array yang diberikan Presenter | Memanggil Model, memakai entity, `foreach`, `count()` |
| **Presenter** (`presenters/`) | Mengambil data dari Model, validasi input, menyusun HTML baris tabel, memanggil View | Bergantung pada kelas konkret (hanya interface) |

### 1.2 Prinsip Strict Passive View
- View **tidak** `require_once` Model atau entity.
- View **tidak** memiliki logika kontrol (`foreach`, `count`); hanya `str_replace` pada template dan escape output.
- Looping data dan pembuatan baris tabel (`buildRowHtml`) dilakukan di Presenter, lalu hasilnya dikirim ke View sebagai string.

### 1.3 Kontrak (Interface)
Semua komunikasi antar lapisan lewat interface:

| File | Interface | Fungsi |
|---|---|---|
| `models/KontrakModel.php` | `KontrakModel`, `KontrakModelLowongan` | Operasi data (get, add, update, delete) |
| `views/KontrakView.php` | `KontrakView`, `KontrakViewLowongan` | `renderDaftar()`, `renderForm()` |
| `presenters/KontrakPresenter.php` | `KontrakPresenter`, `KontrakPresenterLowongan` | `tampilkan*()`, `tambah*()`, `ubah*()`, `hapus*()` |

### 1.4 Desain Database (`mvp_db.sql`)

**kandidat**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, AI | |
| nama | VARCHAR(100) | |
| posisi | VARCHAR(100) | Posisi yang dilamar |
| domisili | VARCHAR(100) | |
| skorTes | INT | 0–100 |
| pengalamanTahun | INT | Tahun |

**lowongan**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, AI | |
| judul | VARCHAR(100) | |
| departemen | VARCHAR(100) | |
| lokasi | VARCHAR(100) | |
| kuota | INT | Minimal 1 |
| status | ENUM('Buka','Tutup') | |

Tidak ada relasi (foreign key) antar kedua tabel.

### 1.5 Struktur Folder

```
MVP_Kandidat/
├── index.php                  # router + perakit Model/View/Presenter
├── mvp_db.sql                 # skema + data contoh
├── README.md
├── models/
│   ├── DB.php                 # koneksi PDO + executeQuery()/getAllResult()
│   ├── Kandidat.php           # entity
│   ├── Lowongan.php           # entity
│   ├── KontrakModel.php       # interface Model
│   ├── TabelKandidat.php      # CRUD kandidat
│   └── TabelLowongan.php      # CRUD lowongan
├── views/
│   ├── KontrakView.php
│   ├── ViewKandidat.php
│   └── ViewLowongan.php
├── presenters/
│   ├── KontrakPresenter.php
│   ├── PresenterKandidat.php
│   └── PresenterLowongan.php
└── template/
    ├── skin.html              # layout utama
    ├── daftar_kandidat.html   # kartu tabel kandidat
    ├── daftar_lowongan.html   # kartu tabel lowongan
    ├── form.html              # form kandidat
    └── form_lowongan.html     # form lowongan
```

---

## 2. Alur Program

### 2.1 Routing (`index.php`)

| Method | URL | Hasil |
|---|---|---|
| GET | `index.php` atau `?page=kandidat` | Daftar kandidat |
| GET | `?page=form_kandidat` | Form tambah kandidat |
| GET | `?page=form_kandidat&id=3` | Form ubah kandidat (data terisi) |
| GET | `?page=lowongan` | Daftar lowongan |
| GET | `?page=form_lowongan[&id=]` | Form tambah / ubah lowongan |
| POST | `page` + `action=tambah` | Simpan data baru |
| POST | `page` + `action=ubah` | Perbarui data |
| POST | `page` + `action=hapus` | Hapus data |

### 2.2 Alur Read (menampilkan daftar)

1. Browser membuka `index.php?page=kandidat`.
2. `index.php` memanggil `PresenterKandidat::tampilkanKandidat()`.
3. Presenter meminta `getAllKandidat()` ke Model (diurutkan `skorTes DESC, id ASC`).
4. Model menjalankan query lalu mengembalikan array objek `Kandidat`.
5. Presenter melakukan loop, menyusun baris `<tr>` lewat `buildRowHtml()` (nilai di-escape), dan menghitung total.
6. Presenter memanggil `View::renderDaftar($rowsHtml, $total)`.
7. View mengisi `daftar_kandidat.html`, membungkusnya dengan `skin.html`, dan mengembalikan HTML ke browser.

### 2.3 Alur Create / Update

1. Klik **+ Tambah Kandidat** → GET `?page=form_kandidat` → `tampilkanFormKandidat(null)` → form kosong, aksi `tambah`.
2. Klik **Edit** → GET `?page=form_kandidat&id=N` → Presenter mengambil `getKandidatById(N)`, mengisi `$formData`, aksi `ubah`. Jika ID tidak ditemukan, diarahkan ke daftar.
3. Form dikirim lewat POST ke `index.php`.
4. `index.php` memanggil `tambahKandidat($_POST)` atau `ubahKandidat($_POST)`.
5. Presenter memvalidasi input:
   - nama, posisi, domisili tidak boleh kosong
   - skor tes 0–100, pengalaman ≥ 0
6. Jika valid, Presenter memanggil `addKandidat()` / `updateKandidat()`; Model menjalankan `INSERT` / `UPDATE` dengan prepared statement.
7. `index.php` melakukan redirect ke daftar (**Post-Redirect-Get**) agar refresh tidak mengirim ulang form.

### 2.4 Alur Delete

1. Klik **Hapus** → muncul konfirmasi browser.
2. Form kecil (POST) mengirim `action=hapus` dan `id`.
3. `index.php` memanggil `hapusKandidat($id)` → Model menjalankan `DELETE ... WHERE id = :id`.
4. Redirect ke daftar.

Modul **Lowongan** memiliki alur yang identik dengan nama method `tampilkanLowongan()`, `tampilkanFormLowongan()`, `tambahLowongan()`, `ubahLowongan()`, `hapusLowongan()`.

### 2.5 Diagram Urutan (Read)

```
Browser      index.php     Presenter        Model         View
   |  GET ?page=kandidat      |               |             |
   |------------>|            |               |             |
   |             |--tampilkanKandidat()------>|             |
   |             |            |--getAllKandidat()---------->|(DB)
   |             |            |<--[Kandidat,...]            |
   |             |            |--renderDaftar(rows,total)-->|
   |             |            |<-------------- HTML --------|
   |<-------- HTML -----------|               |             |
```

---

## 3. Dokumentasi

CRUD tabel kandidat

https://github.com/user-attachments/assets/2b4df9f4-5a1d-48f0-9baa-fe6178f98f5b


Crud tabel lowongan


https://github.com/user-attachments/assets/f2a1d541-8d75-4d63-a72c-56b7f65e4b79


---
