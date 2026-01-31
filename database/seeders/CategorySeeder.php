<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Novel dan cerita fiksi'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku non-fiksi dan referensi'],
            ['name' => 'Teknologi', 'description' => 'Buku tentang teknologi dan pemrograman'],
            ['name' => 'Seni & Budaya', 'description' => 'Buku tentang seni dan budaya'],
            ['name' => 'Pendidikan', 'description' => 'Buku-buku pendidikan'],
            ['name' => 'Biografi', 'description' => 'Buku biografi dan autobiografi'],
            ['name' => 'Anak-anak', 'description' => 'Buku untuk anak-anak'],
            ['name' => 'Bisnis & Ekonomi', 'description' => 'Buku tentang bisnis dan ekonomi'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'slug' => Str::slug($category['name']),
            ]);
        }
    }
}
