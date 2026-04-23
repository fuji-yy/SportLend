<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'petugas')
            ->update(['role' => 'admin']);

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'peminjam') NOT NULL DEFAULT 'peminjam'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'petugas', 'peminjam') NOT NULL DEFAULT 'peminjam'");
    }
};
