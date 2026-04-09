<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin'; // atau gunakan admin@gmail.com
$password = 'admin123';

// Debug: Cari user
$user = User::where(function($query) use ($email) {
    $query->where('email', $email)
          ->orWhere('name', $email);
})->first();

if ($user) {
    echo "User found: " . $user->name . " (" . $user->email . ")\n";
    echo "Password match: " . (Hash::check($password, $user->password) ? 'YES' : 'NO') . "\n";
    echo "Role: " . $user->role . "\n";
} else {
    echo "User not found\n";
    echo "\nAll users in database:\n";
    User::all(['id', 'name', 'email'])->each(function($u) {
        echo "- " . $u->name . " (" . $u->email . ")\n";
    });
}
