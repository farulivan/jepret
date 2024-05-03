# Platform Jepret

Pada tantangan kali ini, kamu akan diminta untuk membuat sebuah platform untuk sharing foto bernama `Jepret`.

Tujuan dari tantangan ini adalah untuk membantumu connecting the dots berbagai konsep yang sudah kamu pelajari saat ini terutama bagaimana membuat codebase yang maintainable dan testable menggunakan `Service & Repository Pattern`. 

Jadi untuk mencapai tujuan ini, kamu akan diminta untuk membuat sebuah webapp sederhana menggunakan **Laravel** namun memiliki view yang berbeda: `Blade` & `REST API + Client`.

## Misi Kamu

1. Bacalah spesifikasi untuk project ini: [disini](./docs/db/mysql.md) & [disini](./docs/api/http_api.md). Kamu juga bisa melihat use case diagram yang ada [disini](./docs/images/usecase_diagram.drawio.svg).
2. Rancang dan buatlah berbagai komponen `Service` & `Repository` yang diperlukan untuk mengimplementasikan spesifikasi ini. Ingat `Service` adalah komponen yang berisi business logic dari suatu aplikasi & `Repository` adalah komponen yang berisi kode untuk mengakses infrastruktur (mis: kode untuk koneksi ke database, kode untuk koneksi ke S3, dll).
3. Buatlah berbagai Blade template dengan menggunakan UI yang sudah disediakan [disini](./ui/).
4. Buatlah controller yang bisa menampilkan berbagai Blade template ini pada pengguna. Pastikan semua fiturnya bisa berjalan dengan baik.
5. Buatlah controller untuk serving API yang sudah dituliskan [disini](./docs/api/http_api.md).
6. Buatlah client app sederhana dengan menggunakan UI yang sudah disediakan [disini](./ui/).
7. Dockerize semua aplikasi yang sudah kamu buat.
8. Buat `docker-compose.yml` untuk menjalankan semua aplikasi yang sudah kamu buat. Untuk tampilan Blade harus bisa diakses lewat `http://localhost:8070`, untuk yang REST API + Client harus bisa diakses lewat `http://localhost:8080`.
9. Buat `Makefile` yang berisi perintah `run` yang digunakan untuk menjalankan file `docker-compose.yml` ini. Pastikan begitu kamu mengeksekusi `make run` seluruh sistem bisa berjalan tanpa perlu setup apapun lagi.
10. Buatlah unit testing (bukan feature testing) untuk setiap `Service` & `Repository` yang sudah kamu buat.

## Kriteria Penilaian

1. Correctness dari fitur-fitur yang kamu diminta untuk implementasikan. Fitur ini termasuk perintah `make run` juga.
2. Desain & readibility dari `Service` & `Repository` yang sudah kamu buat.
3. Readibility dari berbagai unit testing yang sudah kamu buat.