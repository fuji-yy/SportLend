<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
        ]);

        // Create Sample Borrowers
        User::create([
            'name' => 'fuji',
            'email' => 'fuji@gmail.com',
            'password' => Hash::make('fuji123'),
            'role' => 'peminjam',
            'phone' => '083456789012',
            'address' => 'Jl. Pendidik No. 3',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '084567890123',
            'address' => 'Jl. Harapan No. 4',
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Koleksi novel, cerpen, dan karya sastra populer'],
            ['name' => 'Nonfiksi', 'description' => 'Buku pengetahuan, pengembangan diri, dan referensi umum'],
            ['name' => 'Pendidikan', 'description' => 'Buku pendukung belajar, akademik, dan materi pelajaran'],
            ['name' => 'Teknologi', 'description' => 'Buku komputer, pemrograman, dan inovasi digital'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Tools
        $tools = [
            ['category_id' => 1, 'code' => 'BOOK001', 'name' => 'Laskar Pelangi', 'description' => 'Novel inspiratif karya Andrea Hirata', 'quantity' => 20, 'available' => 20, 'condition' => 'baik'],
            ['category_id' => 1, 'code' => 'BOOK002', 'name' => 'Bumi Manusia', 'description' => 'Novel klasik Indonesia karya Pramoedya Ananta Toer', 'quantity' => 18, 'available' => 18, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'BOOK003', 'name' => 'Atomic Habits', 'description' => 'Buku pengembangan diri tentang membangun kebiasaan baik', 'quantity' => 15, 'available' => 15, 'condition' => 'baik'],
            ['category_id' => 3, 'code' => 'BOOK004', 'name' => 'Matematika Dasar', 'description' => 'Buku referensi konsep matematika dasar untuk pelajar', 'quantity' => 12, 'available' => 12, 'condition' => 'baik'],
            ['category_id' => 4, 'code' => 'BOOK005', 'name' => 'Clean Code', 'description' => 'Buku pemrograman tentang praktik menulis kode yang rapi', 'quantity' => 25, 'available' => 25, 'condition' => 'baik'],
            ['category_id' => 4, 'code' => 'BOOK006', 'name' => 'Pengantar Basis Data', 'description' => 'Buku dasar perancangan dan pengelolaan basis data', 'quantity' => 40, 'available' => 40, 'condition' => 'baik'],
        ];

        foreach ($tools as $tool) {
            Tool::create($tool);
        }
    }
}
