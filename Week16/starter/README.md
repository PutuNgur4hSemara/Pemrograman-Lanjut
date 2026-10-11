# Template Proyek Akhir UAS Pemrograman Lanjut

Folder ini berisi kerangka dasar (*boilerplate template*) untuk pengerjaan Proyek Akhir UAS berbasis *Case Method*.

## Struktur Proyek
```text
starter/
├── config/
│   └── Database.php
├── core/
│   ├── Input.php
│   ├── Validator.php
│   └── QueryBuilder.php
├── database/
│   └── schema.sql
└── index.php
```

## Panduan Memulai
1. Tentukan topik studi kasus Anda (Event Ticketing, Lab Asset, atau Auto Repair).
2. Buat skema tabel pada `database/schema.sql`.
3. Manfaatkan library `core/Input.php`, `core/Validator.php`, dan `core/QueryBuilder.php` yang telah Anda bangun di praktikum Minggu 11–15.
4. Buat domain model, repository, dan antarmuka web.
