# Job Queue Simulator (PHP + MySQL)

## Deskripsi Singkat

Job Queue Simulator adalah project backend berbasis **PHP dan MySQL** yang dibuat untuk memahami **alur kerja job queue** di backend, termasuk **background worker**, **retry mechanism**, **failure handling**, dan **dead letter queue**.

Project ini **bukan aplikasi untuk end-user**, melainkan **alat belajar dan portofolio backend engineering** untuk memahami bagaimana sistem backend menangani tugas berat tanpa mengganggu request utama.

---

## Tujuan Project

Project ini dibuat untuk:

* Memahami konsep **job queue** di backend
* Mempelajari pemisahan **request utama dan proses berat**
* Memahami cara kerja **background worker**
* Melatih **failure handling** dan **retry mechanism**
* Mensimulasikan **dead letter queue**
* Menunjukkan **cara berpikir backend engineer**, bukan sekadar CRUD

---

## Gambaran Umum Sistem

Sistem ini memisahkan proses menjadi tiga komponen utama:

1. **Producer** – membuat job dan memasukkannya ke queue
2. **Queue Manager** – mengatur antrian, status, retry, dan dead letter
3. **Worker** – background process yang mengeksekusi job

---

## Alur Kerja Sistem (Flow Queue)

```
EVENT (request / sistem)
   ↓
producer.php
   ↓
QueueManager::push()
   ↓
Database : jobs
   ↓
status = pending
   ↓
────────────────────────────────
(background process berjalan)
────────────────────────────────
   ↓
worker.php (loop hidup)
   ↓
QueueManager::pop()
   ↓
Database transaction BEGIN
   ↓
SELECT job WHERE status = pending
   ↓
LOCK row (FOR UPDATE)
   ↓
UPDATE status = processing
   ↓
Database transaction COMMIT
   ↓
Job object terbentuk
   ↓
worker menerima job
   ↓
switch (job.type)
   ↓
JobProcessor::handle()
   ↓
────────────────────────────────
hasil eksekusi
────────────────────────────────
   ↓
SUCCESS ?
   ├─ YES
   │    ↓
   │  QueueManager::markSuccess()
   │    ↓
   │  status = success
   │
   └─ NO
        ↓
   QueueManager::handleFailure()
        ↓
   attempts + 1
        ↓
   attempts < max_attempts ?
        ├─ YES → status = pending (retry)
        └─ NO  → masuk dead_jobs (dead letter)
```

---

## Struktur Folder Project

```
queue-simulator/
│
├── app/
│   ├── Database.php          
│   ├── QueueManager.php      
│   ├── Job.php               
│   └── JobProcessor/
│       ├── SendEmailJob.php
│       └── GenerateReportJob.php
│
├── scripts/
│   ├── producer.php          
│   └── worker.php            
│
├── database/
│   └── schema.sql            
│
└── README.md
```

---

## Penjelasan Komponen Utama

### 1. Producer (`producer.php`)

Producer berperan sebagai **simulasi request user atau sistem lain**.

Tugas utama:

* Membuat job
* Memasukkan job ke database
* Tidak menjalankan proses berat

Hal ini memastikan request utama tetap cepat dan responsif.

---

### 2. Queue Manager (`QueueManager.php`)

Queue Manager adalah **pusat kendali sistem queue**.

Tanggung jawab:

* Menyimpan job ke queue
* Mengambil job secara aman (locking)
* Mengatur perubahan status job
* Menentukan retry atau dead letter

Queue Manager **tidak menjalankan logic bisnis**.

---

### 3. Worker (`worker.php`)

Worker adalah **background process** yang:

* Berjalan terus menerus
* Mengambil job dari queue
* Menjalankan job sesuai tipenya
* Menangani keberhasilan dan kegagalan job

Worker tidak berinteraksi langsung dengan user.

---

### 4. Job Processor (`JobProcessor/*`)

Job Processor berisi **logic pekerjaan sebenarnya**.

Contoh:

* `SendEmailJob` → simulasi kirim email
* `GenerateReportJob` → simulasi proses berat

Logic di sini boleh lama dan boleh gagal tanpa merusak sistem utama.

---

### 5. Job Entity (`Job.php`)

`Job.php` berfungsi sebagai **representasi data job**, bukan eksekutor.

Fungsinya:

* Menjaga konsistensi struktur job
* Memudahkan reasoning sistem
* Memisahkan data dari proses

---

## Struktur Database

### Tabel `jobs`

Digunakan sebagai **queue utama**.

Kolom penting:

* `status` (`pending`, `processing`, `success`, `dead`)
* `attempts`
* `max_attempts`

### Tabel `dead_jobs`

Digunakan sebagai **dead letter queue**, berisi job yang gagal permanen untuk:

* Debugging
* Audit
* Evaluasi manual

---

## Cara Menjalankan Project

### 1. Import Database

Import file `database/schema.sql` menggunakan phpMyAdmin.

### 2. Enqueue Job

Jalankan perintah berikut:

```bash
php scripts/producer.php
```

### 3. Jalankan Worker

Di terminal lain:

```bash
php scripts/worker.php
```

### 4. Monitoring

Cek tabel `jobs` dan `dead_jobs` di phpMyAdmin untuk melihat perubahan status job.

---

## Catatan Penting

* Project ini adalah **simulator pembelajaran**, bukan sistem production
* Belum memiliki mekanisme recovery untuk job stuck
* Fokus utama adalah **alur backend dan mindset queue**

---

## Kesimpulan

Project ini menunjukkan bahwa backend modern:

* Tidak menjalankan tugas berat di request utama
* Menggunakan queue untuk menjaga stabilitas sistem
* Menganggap failure sebagai kondisi normal
* Menggunakan retry dan dead letter mechanism

Project ini dibuat sebagai **latihan berpikir sistem**, bukan sekadar latihan coding.
