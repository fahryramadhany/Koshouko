<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        $this->call(RoleSeeder::class);

        // Seed categories
        $this->call(CategorySeeder::class);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'phone' => '081234567890',
            'member_id' => 'ADM001',
            'status' => 'active',
        ]);

        // Create Pustakawan User
        $pustakawan = User::factory()->create([
            'name' => 'Pustakawan',
            'email' => 'pustakawan@perpustakaan.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'phone' => '081234567891',
            'member_id' => 'PUS001',
            'status' => 'active',
        ]);

        // Create Member Users
        $memberIds = ['MBR001', 'MBR002', 'MBR003'];
        foreach ($memberIds as $memberId) {
            User::factory()->create([
                'role_id' => 3,
                'member_id' => $memberId,
                'status' => 'active',
            ]);
        }

        // Create Sample Books
        $bookTitles = [
            ['title' => 'Laravel untuk Pemula', 'author' => 'Andi Wijaya', 'category_id' => 3],
            ['title' => 'Perjalanan Seribu Mil', 'author' => 'Anita Permata', 'category_id' => 1],
            ['title' => 'Hidup Sederhana', 'author' => 'Budi Santoso', 'category_id' => 2],
            ['title' => 'Seni Rupa Tradisional', 'author' => 'Cindy Wijaya', 'category_id' => 4],
            ['title' => 'Metodologi Penelitian', 'author' => 'Drs. Hendra', 'category_id' => 5],
            ['title' => 'Steve Jobs: Visioner', 'author' => 'Walter Isaacson', 'category_id' => 6],
            ['title' => 'Petualangan Anak Harimau', 'author' => 'Sri Utami', 'category_id' => 7],
            ['title' => 'Strategi Bisnis Digital', 'author' => 'Muhammad Ali', 'category_id' => 8],
        ];

        foreach ($bookTitles as $book) {
            Book::create([
                'isbn' => Str::random(13),
                'title' => $book['title'],
                'author' => $book['author'],
                'category_id' => $book['category_id'],
                'publisher' => 'Penerbit Indonesia',
                'publication_year' => 2023,
                'total_copies' => 5,
                'available_copies' => 5,
                'status' => 'available',
                'location' => 'Rak A' . rand(1, 5),
                'pages' => rand(200, 500),
                'description' => 'Buku yang sangat menarik dan informatif untuk dibaca.',
            ]);
        }
    }
}
