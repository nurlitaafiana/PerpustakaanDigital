<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna / Petugas
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.sch.id'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'password' => Hash::make('password'),
                'role' => 'Administrator',
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.sch.id'],
            [
                'name' => 'Siti Nurhaliza, A.Md',
                'password' => Hash::make('password'),
                'role' => 'Petugas',
            ]
        );

        // 2. Data Buku Sekolah
        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'kategori' => 'Fiksi',
                'stok' => 4,
                'deskripsi' => 'Novel inspiratif tentang perjuangan sepuluh anak di Belitung dalam menempuh pendidikan di tengah keterbatasan fasilitas sekolah.',
            ],
            [
                'judul' => 'Bumi',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2014,
                'kategori' => 'Fiksi',
                'stok' => 5,
                'deskripsi' => 'Kisah petualangan Raib, Seli, dan Ali menjelajahi dunia paralel klan Bulan, Matahari, dan Bintang.',
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'A. Fuadi',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2009,
                'kategori' => 'Fiksi',
                'stok' => 3,
                'deskripsi' => 'Kisah persahabatan enam santri di Pesantren Madani yang memegang teguh mantra sakti "Man Jadda Wajada".',
            ],
            [
                'judul' => 'Fisika Dasar untuk SMA/MA Kelas XI',
                'penulis' => 'Marthen Kanginan',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2021,
                'kategori' => 'Sains & Teknologi',
                'stok' => 7,
                'deskripsi' => 'Buku teks standar kurikulum nasional membahas dinamika gerak, hukum gravitasi, gelombang, termodinamika, dan optika.',
            ],
            [
                'judul' => 'Pengantar Kecerdasan Buatan dan Robotika',
                'penulis' => 'Eko Prasetyo',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2022,
                'kategori' => 'Komputer & IT',
                'stok' => 4,
                'deskripsi' => 'Buku pengenalan konsep machine learning, neural network, dan penerapannya di dunia modern secara aplikatif.',
            ],
            [
                'judul' => 'Biologi Sel dan Molekuler',
                'penulis' => 'Campbell & Reece',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2020,
                'kategori' => 'Sains & Teknologi',
                'stok' => 3,
                'deskripsi' => 'Buku referensi lengkap struktur sel makhluk hidup, replikasi DNA, sintesis protein, dan bioteknologi modern.',
            ],
            [
                'judul' => 'Kalkulus dan Geometri Analitis Jilid 1',
                'penulis' => 'Edwin J. Purcell',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2019,
                'kategori' => 'Matematika',
                'stok' => 5,
                'deskripsi' => 'Membahas konsep fungsi, limit, turunan diferensial, serta integral dasar beserta penerapannya.',
            ],
            [
                'judul' => 'Matematika Diskrit Edisi Revisi',
                'penulis' => 'Rinaldi Munir',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2022,
                'kategori' => 'Matematika',
                'stok' => 6,
                'deskripsi' => 'Konsep himpunan, logika matematika, teori graf, relasi, kombinatorika, dan aljabar boolean.',
            ],
            [
                'judul' => 'Sejarah Nasional Indonesia: Masa Pergerakan',
                'penulis' => 'Sartono Kartodirdjo',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => 2018,
                'kategori' => 'Sejarah',
                'stok' => 2,
                'deskripsi' => 'Kajian sejarah kritis kebangkitan nasional dari Budi Utomo, Sumpah Pemuda, hingga kemerdekaan RI 1945.',
            ],
            [
                'judul' => 'Tata Bahasa Baku Bahasa Indonesia Edisi IV',
                'penulis' => 'Badan Pengembangan Bahasa Kemendikbud',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => 2021,
                'kategori' => 'Bahasa & Sastra',
                'stok' => 8,
                'deskripsi' => 'Pedoman resmi tata bahasa fonologi, morfologi, sintaksis, dan semantik Bahasa Indonesia.',
            ],
            [
                'judul' => 'Pemrograman Web Modern dengan Laravel',
                'penulis' => 'Ahmad Rian Maulana',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2024,
                'kategori' => 'Komputer & IT',
                'stok' => 6,
                'deskripsi' => 'Panduan lengkap membangun sistem informasi sekolah berbasis web menggunakan Laravel, Eloquent ORM, dan Bootstrap.',
            ],
            [
                'judul' => 'Ensiklopedia Sains dan Alam Semesta',
                'penulis' => 'Tim Editor Sains Dunia',
                'penerbit' => 'Mizan',
                'tahun_terbit' => 2023,
                'kategori' => 'Ensiklopedia',
                'stok' => 3,
                'deskripsi' => 'Buku visual ensiklopedia astronomi, tata surya, gunung berapi, samudra, dan ekosistem bumi.',
            ],
        ];

        $createdBooks = [];
        foreach ($books as $b) {
            $createdBooks[] = Book::firstOrCreate(
                ['judul' => $b['judul']],
                $b
            );
        }

        // 3. Data Anggota Siswa
        $members = [
            [
                'nama' => 'Alfiana',
                'nis' => '20241001',
                'kelas' => 'XII RPL 1',
                'email' => 'alfiana@perpustakaan.sch.id',
                'nomor_telepon' => '081234567801',
                'alamat' => 'Jl. Merdeka No. 12, Kel. Sukajaya',
            ],
            [
                'nama' => 'Nabila Putri Cahyani',
                'nis' => '20241002',
                'kelas' => 'XII MIPA 2',
                'email' => 'nabila.putri@siswa.sch.id',
                'nomor_telepon' => '081234567802',
                'alamat' => 'Jl. Melati No. 45, Komplek Permai',
            ],
            [
                'nama' => 'Rizky Pratama',
                'nis' => '20241003',
                'kelas' => 'XI TKJ 1',
                'email' => 'rizky.pratama@siswa.sch.id',
                'nomor_telepon' => '081234567803',
                'alamat' => 'Jl. Pahlawan Gg. 3 No. 8',
            ],
            [
                'nama' => 'Dinda Ayu Lestari',
                'nis' => '20241004',
                'kelas' => 'XI IPS 1',
                'email' => 'dinda.ayu@siswa.sch.id',
                'nomor_telepon' => '081234567804',
                'alamat' => 'Jl. Kenanga Asri No. 19',
            ],
            [
                'nama' => 'Muhammad Zidan',
                'nis' => '20241005',
                'kelas' => 'X RPL 2',
                'email' => 'zidan.m@siswa.sch.id',
                'nomor_telepon' => '081234567805',
                'alamat' => 'Jl. Diponegoro No. 88',
            ],
            [
                'nama' => 'Syifa Aulia Rahma',
                'nis' => '20241006',
                'kelas' => 'X MIPA 1',
                'email' => 'syifa.rahma@siswa.sch.id',
                'nomor_telepon' => '081234567806',
                'alamat' => 'Jl. Anggrek No. 102',
            ],
            [
                'nama' => 'Bagas Arya Wicaksono',
                'nis' => '20241007',
                'kelas' => 'XII TKJ 2',
                'email' => 'bagas.arya@siswa.sch.id',
                'nomor_telepon' => '081234567807',
                'alamat' => 'Jl. Gatot Subroto No. 51',
            ],
            [
                'nama' => 'Tiara Maharani',
                'nis' => '20241008',
                'kelas' => 'XI MIPA 3',
                'email' => 'tiara.maharani@siswa.sch.id',
                'nomor_telepon' => '081234567808',
                'alamat' => 'Jl. Cempaka Putih No. 27',
            ],
        ];

        $createdMembers = [];
        foreach ($members as $m) {
            $createdMembers[] = Member::firstOrCreate(
                ['nis' => $m['nis']],
                $m
            );
        }

        // Akun Demo Siswa / User (Alfiana)
        User::updateOrCreate(
            ['email' => 'alfiana@perpustakaan.sch.id'],
            [
                'name' => 'Alfiana',
                'password' => Hash::make('12345678'),
                'role' => 'Siswa',
                'member_id' => $createdMembers[0]->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'siswa@perpustakaan.sch.id'],
            [
                'name' => 'Alfiana',
                'password' => Hash::make('12345678'),
                'role' => 'Siswa',
                'member_id' => $createdMembers[0]->id,
            ]
        );

        // 4. Data Peminjaman Demo (Aktif, Dikembalikan, dan Terlambat)
        $today = Carbon::now();

        $borrowings = [
            // Dipinjam (Masih aktif dan belum jatuh tempo)
            [
                'user_id' => $admin->id,
                'member_id' => $createdMembers[0]->id,
                'book_id' => $createdBooks[0]->id, // Laskar Pelangi
                'tanggal_pinjam' => $today->copy()->subDays(3)->toDateString(),
                'tanggal_kembali' => $today->copy()->addDays(4)->toDateString(),
                'tanggal_pengembalian_aktual' => null,
                'status' => 'Dipinjam',
                'catatan' => 'Peminjaman untuk tugas resensi bahasa Indonesia',
            ],
            [
                'user_id' => $petugas->id,
                'member_id' => $createdMembers[1]->id,
                'book_id' => $createdBooks[3]->id, // Fisika Dasar
                'tanggal_pinjam' => $today->copy()->subDays(2)->toDateString(),
                'tanggal_kembali' => $today->copy()->addDays(5)->toDateString(),
                'tanggal_pengembalian_aktual' => null,
                'status' => 'Dipinjam',
                'catatan' => 'Persiapan olimpiade sains madrasah/sekolah',
            ],
            [
                'user_id' => $admin->id,
                'member_id' => $createdMembers[4]->id,
                'book_id' => $createdBooks[10]->id, // Pemrograman Web Modern
                'tanggal_pinjam' => $today->copy()->subDays(1)->toDateString(),
                'tanggal_kembali' => $today->copy()->addDays(6)->toDateString(),
                'tanggal_pengembalian_aktual' => null,
                'status' => 'Dipinjam',
                'catatan' => 'Materi praktik kejuruan RPL',
            ],

            // Terlambat (Tanggal kembali sudah lewat dan belum dikembalikan)
            [
                'user_id' => $petugas->id,
                'member_id' => $createdMembers[2]->id,
                'book_id' => $createdBooks[4]->id, // Pengantar Kecerdasan Buatan
                'tanggal_pinjam' => $today->copy()->subDays(14)->toDateString(),
                'tanggal_kembali' => $today->copy()->subDays(7)->toDateString(),
                'tanggal_pengembalian_aktual' => null,
                'status' => 'Terlambat',
                'catatan' => 'Sudah diingatkan via WhatsApp wali kelas',
            ],
            [
                'user_id' => $admin->id,
                'member_id' => $createdMembers[6]->id,
                'book_id' => $createdBooks[8]->id, // Sejarah Nasional
                'tanggal_pinjam' => $today->copy()->subDays(10)->toDateString(),
                'tanggal_kembali' => $today->copy()->subDays(3)->toDateString(),
                'tanggal_pengembalian_aktual' => null,
                'status' => 'Terlambat',
                'catatan' => 'Tenggat waktu lewat 3 hari',
            ],

            // Dikembalikan (Sudah selesai dan tepat waktu / telah dikembalikan)
            [
                'user_id' => $petugas->id,
                'member_id' => $createdMembers[3]->id,
                'book_id' => $createdBooks[1]->id, // Bumi
                'tanggal_pinjam' => $today->copy()->subDays(20)->toDateString(),
                'tanggal_kembali' => $today->copy()->subDays(13)->toDateString(),
                'tanggal_pengembalian_aktual' => $today->copy()->subDays(14)->toDateString(),
                'status' => 'Dikembalikan',
                'catatan' => 'Buku kembali dalam kondisi sangat baik',
            ],
            [
                'user_id' => $admin->id,
                'member_id' => $createdMembers[5]->id,
                'book_id' => $createdBooks[7]->id, // Matematika Diskrit
                'tanggal_pinjam' => $today->copy()->subDays(18)->toDateString(),
                'tanggal_kembali' => $today->copy()->subDays(11)->toDateString(),
                'tanggal_pengembalian_aktual' => $today->copy()->subDays(12)->toDateString(),
                'status' => 'Dikembalikan',
                'catatan' => 'Selesai peminjaman tepat waktu',
            ],
            [
                'user_id' => $petugas->id,
                'member_id' => $createdMembers[7]->id,
                'book_id' => $createdBooks[11]->id, // Ensiklopedia Sains
                'tanggal_pinjam' => $today->copy()->subDays(12)->toDateString(),
                'tanggal_kembali' => $today->copy()->subDays(5)->toDateString(),
                'tanggal_pengembalian_aktual' => $today->copy()->subDays(6)->toDateString(),
                'status' => 'Dikembalikan',
                'catatan' => 'Buku kembali dalam keadaan rapi',
            ],
        ];

        foreach ($borrowings as $borrowing) {
            Borrowing::create($borrowing);
        }
    }
}
