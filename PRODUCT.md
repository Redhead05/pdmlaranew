# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- **Admin (operator sekretariat BAN-PDM Jatim)** — mengelola data induk, tahapan akreditasi, pembentukan/pasangan tim asesor, validasi, surat tugas, dan tiket dukungan. (inferred: admin adalah staf internal badan akreditasi)
- **Asesor (asesor akreditasi eksternal)** — menerima penugasan, menyatakan kesanggupan, melaksanakan visitasi, mengunggah bukti, menandatangani presensi, dan membuka tiket dukungan.
- **Adminlanding (pengelola situs publik)** — mengelola konten landing publik (home, berita, galeri, pegawai, FAQ) dan chat tamu.
- **User (pengunjung terdaftar)** — peran tersedia di seeder tetapi belum memiliki modul dashboard tersendiri; akses publik dibuka lewat halaman landing dan presensi publik tanpa login khusus peran ini. (inferred: peran `user` masih bertumpu pada halaman publik)

## Product Purpose

Mendigitalisasi logistik akreditasi sekolah/lembaga milik BAN-PDM Jawa Timur: dari data induk lembaga dan pengelolaan tahap, pernyataan kesanggupan asesor, pembentukan tim & pemasangan asesor–lembaga, penerbitan surat tugas, pengumpulan bukti visitasi, validasi, hingga sertifikasi dan tiket dukungan. Keberhasilan berarti setiap siklus akreditasi berjalan dengan penugasan yang terlacak dan bisa diaudit serta bukti visitasi yang lengkap, menggantikan proses manual/spreadsheet.

## Positioning

Satu sistem web dengan akses berbasis peran yang mengubah alur pemasangan/penugasan akreditasi menjadi proses yang reversibel, terkunci, dan bisa diekspor: pembentukan tim otomatis dengan draft/finalisasi/kunci-buka, aturan pemasangan, surat tugas bernomor, serta bukti visitasi terdigitalisasi plus presensi berbasis tanda tangan. Alat admin generik tidak bisa mengklaim objek alur spesifik BAN-PDM (tahap, kesanggupan, rumpun, lintas rumpun, surat tugas).

## Operating Context

Digunakan oleh operator dan asesor BAN-PDM Jatim yang tersebar di seluruh Jawa Timur. Asesor adalah penilai eksternal dengan atribut wilayah (hierarki kabupaten/kota → kecamatan → desa) dan rumpun keilmuan (termasuk lintas rumpun). Alur kerja melibatkan impor/ekspor CSV data pemasangan, dokumen (surat tugas, bukti) yang dihasilkan/diunduh, serta presensi digital dengan tanda tangan saat kunjungan. Situs publik melayani audiens luas dengan berita, galeri, FAQ, dan chat tamu; halaman presensi publik melayani acara umum dan internal.

## Capabilities and Constraints

**Fungsionalitas terkonfirmasi (dari routes/migrations):**
- Manajemen pengguna & detail asesor (unit kerja, tipe asesor, lintas rumpun, KTP, wilayah) dengan toggle status/lokasi/flag.
- Data induk: lembaga (master + detail), tahap, wilayah (kabkot/kecamatan/desa), sertifikasi, kalender.
- Presensi/kehadiran tiga tipe (`asesor`, `internal`, `umum`) dengan tandatangan digital dan detail form + sertifikat.
- Kesanggupan asesor (bisa / tidak bisa / belum mengisi) dan pembentukan tim (generate, draft, finalize, assign/unassign, reopen).
- Pemasangan asesor–lembaga: generate, aturan pasangan, slot, kunci/buka, impor/ekspor.
- Validasi kesanggupan & lembaga: set status, bulk-set, assign/unassign asesor, auto-pair, kunci/buka.
- Surat tugas bernomor & berslug, kirim + unduh, dengan riwayat.
- Berkas visitasi (unggah foto/dokumen, approve/reject) dan tiket dukungan (buka/respond/close).
- Notifikasi, chat tamu + chat adminlanding (realtime), profil.
- CMS publik: home, berita (+detail), galeri, pegawai, FAQ.

**Kendala teknis & produk:**
- Akses berbasis peran (Spatie Permission): `admin`, `adminlanding`, `asesor`, `user`.
- Soft delete pada data utama; kunci pemasangan di level tahap dengan override asesor.
- Zona waktu `Asia/Jakarta`; terminologi domain berbahasa Indonesia.
- Belum diputuskan: cakupan modul peran `user` (belum ada dashboard khusus).

## Brand Commitments

- Nama aplikasi: **BAN PDM JAWA TIMUR** (dari `APP_NAME`).
- Identitas badan akreditasi pendidikan Indonesia. (inferred: BAN-PDM = Badan Akreditasi Nasional PAUD/Dikdas/Dikmen)
- Aset logo: `public/assets/logo_BANPDMJATIM.png`.

## Evidence on Hand

- Logo: `public/assets/logo_BANPDMJATIM.png`.
- Sumber desain Trezo lengkap: `public/assets/scss/` + `public/assets/images/`.
- Skema domain sebagai bukti: `database/migrations/` (59 migrasi).
- Dokumen desain: `DESIGN.md`, `DESIGN-attendance.md`.
- Tidak ada testimoni/studi kasus/konten pemasaran nyata — jangan difabrikasi pada pekerjaan visual berikutnya.

## Product Principles

1. **Keterlacakan & auditabilitas** — setiap penugasan dan surat tugas terkunci, bernomor, dan dapat dibatalkan/dibuka ulang.
2. **Pemisahan peran** — operator (admin) menyelenggarakan, asesor mengeksekusi, admin konten mengelola situs publik.
3. **Integritas data pemasangan** — penugasan dan bukti harus mencerminkan kondisi nyata, tidak pernah difabrikasi; impor/ekspor CSV menjaga kompatibilitas proses offline.
4. **Bukti visitasi lengkap** — setiap kunjungan berakhir dengan bukti yang lengkap dan tervalidasi.
5. **Indonesia-first** — terminologi dan zona waktu (Asia/Jakarta) melekat pada konteks akreditasi, bukan diterjemahkan.
