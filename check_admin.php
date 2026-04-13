<?php

$_ENV['APP_ENV'] = 'local';
$_ENV['APP_DEBUG'] = 'true';

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::where('role', 'admin')->first();

if ($user) {
    echo "Admin found: {$user->email}\n";
    echo "ID: {$user->id}\n";
} else {
    echo "No admin found. Creating one...\n";
    $admin = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "Admin created: {$admin->email}\n";
    echo "Password: password123\n";
}
