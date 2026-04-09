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

        // Create Staff User
        User::create([
            'name' => 'petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'phone' => '082345678901',
            'address' => 'Jl. Petugas No. 2',
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
            ['name' => 'Peralatan Elektronik', 'description' => 'Alat-alat yang menggunakan listrik'],
            ['name' => 'Peralatan Olahraga', 'description' => 'Alat-alat untuk kegiatan olahraga'],
            ['name' => 'Peralatan Kampus', 'description' => 'Alat-alat untuk aktivitas kampus'],
            ['name' => 'Peralatan Multimedia', 'description' => 'Alat-alat untuk presentasi dan recording'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Tools
        $tools = [
            ['category_id' => 2, 'code' => 'SPORT001', 'name' => 'Bola Sepak', 'description' => 'Bola sepak standar internasional FIFA', 'quantity' => 20, 'available' => 20, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'SPORT002', 'name' => 'Bola Voli', 'description' => 'Bola voli standar internasional', 'quantity' => 18, 'available' => 18, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'SPORT003', 'name' => 'Bola Basket', 'description' => 'Bola basket standar internasional NBA', 'quantity' => 15, 'available' => 15, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'SPORT004', 'name' => 'Bola Handball', 'description' => 'Bola handball standar untuk permainan', 'quantity' => 12, 'available' => 12, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'SPORT005', 'name' => 'Matras', 'description' => 'Matras untuk senam dan latihan', 'quantity' => 25, 'available' => 25, 'condition' => 'baik'],
            ['category_id' => 2, 'code' => 'SPORT006', 'name' => 'Cone', 'description' => 'Cone untuk latihan dribbling dan agility', 'quantity' => 40, 'available' => 40, 'condition' => 'baik'],
        ];

        foreach ($tools as $tool) {
            Tool::create($tool);
        }
    }
}
