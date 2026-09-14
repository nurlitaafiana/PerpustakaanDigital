<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    /**
     * Test redirect ke login untuk user unauthenticated.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test autentikasi login berhasil.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::first() ?? User::factory()->create([
            'email' => 'testing@perpustakaan.sch.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', // dari seeder
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test registrasi pengguna baru berhasil.
     */
    public function test_user_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Petugas Baru Uji Coba',
            'email' => 'petugas.baru@perpustakaan.sch.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'email' => 'petugas.baru@perpustakaan.sch.id',
            'name' => 'Petugas Baru Uji Coba',
            'role' => 'Petugas',
        ]);
    }

    /**
     * Test dashboard menampilkan view dengan statistik.
     */
    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Perpustakaan');
        $response->assertSee('Total Judul Buku');
        $response->assertSee('Anggota Terdaftar');
    }

    /**
     * Test CRUD data buku.
     */
    public function test_can_create_and_view_book(): void
    {
        $user = User::first();

        $bookData = [
            'judul' => 'Belajar Laravel dan Bootstrap 5',
            'penulis' => 'Penulis Percobaan',
            'penerbit' => 'Pustaka Uji',
            'tahun_terbit' => 2024,
            'kategori' => 'Komputer & IT',
            'stok' => 10,
            'deskripsi' => 'Buku panduan praktis untuk tes otomatis.',
        ];

        $response = $this->actingAs($user)->post('/books', $bookData);
        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'judul' => 'Belajar Laravel dan Bootstrap 5',
            'stok' => 10,
        ]);

        // Cek halaman index buku memuat buku yang baru
        $indexResponse = $this->actingAs($user)->get('/books');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Belajar Laravel dan Bootstrap 5');
    }

    /**
     * Test CRUD data anggota.
     */
    public function test_can_create_and_update_member(): void
    {
        $user = User::first();

        $memberData = [
            'nama' => 'Budi Santoso Junior',
            'nis' => '99887766',
            'kelas' => 'X RPL 3',
            'email' => 'budi.jr@siswa.sch.id',
            'nomor_telepon' => '081234567899',
            'alamat' => 'Jl. Pendidikan No. 44',
        ];

        $response = $this->actingAs($user)->post('/members', $memberData);
        $response->assertRedirect(route('members.index'));

        $this->assertDatabaseHas('members', [
            'nis' => '99887766',
            'nama' => 'Budi Santoso Junior',
        ]);

        $member = Member::where('nis', '99887766')->first();

        // Update anggota
        $updateResponse = $this->actingAs($user)->put(route('members.update', $member->id), [
            'nama' => 'Budi Santoso Junior (Updated)',
            'nis' => '99887766',
            'kelas' => 'XI RPL 3',
            'email' => 'budi.jr@siswa.sch.id',
            'nomor_telepon' => '081234567899',
            'alamat' => 'Jl. Pendidikan No. 44 Baru',
        ]);

        $updateResponse->assertRedirect(route('members.index'));
        $this->assertDatabaseHas('members', [
            'nama' => 'Budi Santoso Junior (Updated)',
            'kelas' => 'XI RPL 3',
        ]);
    }

    /**
     * Test manajemen stok saat peminjaman dan pengembalian.
     */
    public function test_borrowing_decrements_stock_and_return_increments_stock(): void
    {
        $user = User::first();
        $member = Member::first();

        $book = Book::create([
            'judul' => 'Buku Uji Stok Dinamis',
            'penulis' => 'Tester',
            'penerbit' => 'Test Publisher',
            'tahun_terbit' => 2024,
            'kategori' => 'Sains',
            'stok' => 5,
        ]);

        // 1. Catat peminjaman
        $response = $this->actingAs($user)->post('/borrowings', [
            'member_id' => $member->id,
            'book_id' => $book->id,
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'catatan' => 'Tes peminjaman stok',
        ]);

        $response->assertRedirect(route('borrowings.index'));

        // Stok harus berkurang jadi 4
        $book->refresh();
        $this->assertEquals(4, $book->stok);

        $borrowing = Borrowing::where('book_id', $book->id)->first();
        $this->assertNotNull($borrowing);
        $this->assertEquals('Dipinjam', $borrowing->status);

        // 2. Kembalikan buku
        $returnResponse = $this->actingAs($user)->post(route('borrowings.return', $borrowing->id));
        $returnResponse->assertRedirect(route('borrowings.index'));

        // Stok harus kembali menjadi 5
        $book->refresh();
        $this->assertEquals(5, $book->stok);

        $borrowing->refresh();
        $this->assertEquals('Dikembalikan', $borrowing->status);
        $this->assertNotNull($borrowing->tanggal_pengembalian_aktual);
    }

    /**
     * Test update profil pengguna.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru Petugas',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru Petugas',
        ]);
    }
}
