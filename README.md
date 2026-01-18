# Job Queue Simulator (PHP + MySQL)

## Deskripsi

**Job Queue Simulator** adalah project backend berbasis **PHP dan MySQL** untuk mempelajari **alur kerja job queue**, termasuk **background worker**, **retry mechanism**, dan **dead letter queue**.

Project ini dibuat sebagai **alat belajar dan portofolio backend engineering**, bukan aplikasi end-user.

---

## Tujuan

Project ini bertujuan untuk memahami:

* Konsep **job queue** dan background processing
* Pemisahan **request utama** dan **proses berat**
* Cara kerja **worker asynchronous**
* **Retry mechanism** dan **failure handling**
* **Dead letter queue** untuk job gagal permanen

---

## Gambaran Sistem

Sistem terdiri dari tiga komponen utama:

1. **Producer** – membuat dan mendaftarkan job
2. **Queue Manager** – mengelola antrean, status, retry, dan dead job
3. **Worker** – background process yang mengeksekusi job

---

## Alur Kerja Queue

```
producer
   ↓
QueueManager::push
   ↓
jobs (pending)
   ↓
worker (background)
   ↓
QueueManager::pop (processing)
   ↓
JobProcessor
   ↓
success / retry / dead
```

---

## Struktur Project

```
queue-simulator/
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

## Komponen Utama

* **Producer**
  Membuat job dan memasukkannya ke queue tanpa menjalankan proses berat.

* **Queue Manager**
  Mengatur antrean job, status, retry, dan dead letter tanpa logic bisnis.

* **Worker**
  Background process yang mengeksekusi job dan mencatat hasil eksekusi.

* **Job Processor**
  Berisi logic kerja aktual (simulasi job ringan dan berat).

* **Job Entity**
  Representasi data job untuk menjaga konsistensi dan keterbacaan sistem.

---

## Struktur Database

* **jobs**
  Queue utama (`pending`, `processing`, `success`, `dead`)

* **dead_jobs**
  Dead letter queue untuk job gagal permanen

---

## Cara Menjalankan

1. Import database:

   ```bash
   database/schema.sql
   ```

2. Enqueue job:

   ```bash
   php scripts/producer.php
   ```

3. Jalankan worker:

   ```bash
   php scripts/worker.php
   ```

4. Monitoring melalui tabel `jobs` dan `dead_jobs`

---

## Catatan

* Project ini adalah **simulator pembelajaran**
* Tidak ditujukan untuk production
* Fokus pada **alur dan cara berpikir backend engineer**

---

## License

MIT License
