# Job Queue Simulator (PHP + MySQL)

## Deskripsi

**Job Queue Simulator** adalah simulasi sistem **background job queue** yang dibangun menggunakan **PHP dan MySQL**.
Project ini dibuat untuk memahami bagaimana sistem queue bekerja di backend engineering, termasuk **worker processing, retry mechanism, dead letter queue, dan monitoring dashboard**.

Simulator ini meniru konsep yang digunakan pada sistem queue seperti:

* Laravel Queue
* RabbitMQ Worker
* AWS SQS Worker
* Background Job Processing

Project ini ditujukan sebagai **alat pembelajaran dan portfolio backend engineering**, bukan aplikasi end-user.

---

# Tujuan Project

Project ini bertujuan untuk mempelajari dan mendemonstrasikan konsep:

* Job Queue Architecture
* Background Worker Processing
* Retry Mechanism
* Dead Letter Queue
* Job Execution Monitoring
* Real-time Queue Dashboard
* Separation between Producer, Queue Engine, and Worker

---

# Gambaran Sistem

Sistem terdiri dari beberapa komponen utama:

1. **Producer**
   Membuat job dan memasukkannya ke dalam queue tanpa menjalankan proses berat.

2. **Queue Manager**
   Bertindak sebagai engine queue yang mengelola:

   * enqueue job
   * dequeue job
   * status job
   * retry mechanism
   * dead letter queue

3. **Worker**
   Background process yang mengambil job dari queue dan mengeksekusinya.

4. **Job Processor**
   Berisi logic kerja aktual dari masing-masing job.

5. **Queue Dashboard**
   Halaman monitoring berbasis web yang menampilkan statistik queue secara **real-time**.

---

# Arsitektur Sistem

Producer tidak menjalankan pekerjaan berat secara langsung.
Job akan dimasukkan ke dalam queue dan diproses oleh worker di background.

```
Producer
   ↓
QueueManager::push
   ↓
Database Queue (jobs table)
   ↓
Worker Process
   ↓
QueueManager::pop
   ↓
Job Processor
   ↓
Success / Retry / Dead
```

---

# Struktur Project

```
queue-simulator/
│
├── app/
│   ├── Database.php
│   ├── QueueManager.php
│   ├── Job.php
│   ├── ProcessorFactory.php
│   │
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
├── public/
│   ├── index.php
│   ├── dashboard.php
│   └── dashboard-data.php
│
└── README.md
```

---

# Komponen Utama

### Producer

Producer membuat job baru dan memasukkannya ke queue tanpa memproses pekerjaan berat.

Contoh job yang dibuat:

* send_email
* generate_report

---

### Queue Manager

Queue Manager bertanggung jawab untuk:

* Menyimpan job ke queue
* Mengambil job dari queue
* Mengatur status job
* Mengelola retry
* Memindahkan job gagal ke dead letter queue

Status job yang digunakan:

```
pending
processing
success
dead
```

---

### Worker

Worker adalah background process yang berjalan secara terus-menerus untuk memproses job.

Worker akan:

1. mengambil job dari queue
2. menjalankan job processor
3. mencatat waktu eksekusi
4. menentukan success atau retry

---

### Job Processor

Job Processor berisi logic kerja aktual dari job.

Contoh processor yang tersedia:

* **SendEmailJob**
  Simulasi pengiriman email.

* **GenerateReportJob**
  Simulasi proses report dengan workload lebih berat.

Processor juga dapat menghasilkan **random failure** untuk mensimulasikan retry mechanism.

---

### Job Entity

Job entity (`Job.php`) digunakan sebagai representasi data job di dalam sistem untuk menjaga konsistensi struktur data antar komponen.

---

# Struktur Database

### jobs

Queue utama yang menyimpan semua job.

Status yang digunakan:

```
pending
processing
success
dead
```

Kolom penting:

```
id
type
payload
status
attempts
max_attempts
execution_ms
created_at
```

---

### dead_jobs

Dead letter queue yang menyimpan job yang gagal setelah mencapai batas retry.

---

# Queue Dashboard

Project ini juga menyediakan **dashboard monitoring** sederhana.

Dashboard menampilkan:

* Pending Jobs
* Processing Jobs
* Success Jobs
* Dead Jobs
* Execution Time Job Terakhir
* List Job Terbaru

Dashboard melakukan **auto refresh menggunakan AJAX polling** sehingga status queue dapat dipantau secara real-time.

---

# Cara Menjalankan Project

### 1. Import Database

```
database/schema.sql
```

---

### 2. Jalankan Producer

Producer akan membuat beberapa job dan memasukkannya ke queue.

```
php scripts/producer.php
```

---

### 3. Jalankan Worker

Worker akan mengambil job dari queue dan mengeksekusinya.

```
php scripts/worker.php
```

---

### 4. Jalankan Dashboard

Jalankan PHP built-in server:

```
php -S localhost:8000 -t public
```

Buka browser:

```
http://localhost:8000
```

---

# Contoh Output Worker

```
Worker started...

[PROCESSING] Job ID 12 | Type: send_email
Sending email...
[SUCCESS] Job 12 finished in 1020 ms

[PROCESSING] Job ID 13 | Type: generate_report
Generating report (5000 rows)...
[SUCCESS] Job 13 finished in 3210 ms
```

---

# Catatan

Project ini dibuat untuk **tujuan pembelajaran backend engineering** dan bukan untuk penggunaan production.

Fokus utama project ini adalah memahami:

* queue architecture
* background job processing
* failure handling
* retry mechanism
* monitoring system

---

# License

MIT License
