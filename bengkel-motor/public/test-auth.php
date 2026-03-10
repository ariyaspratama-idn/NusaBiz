<?php
use Illuminate\Support\Facades\Auth;

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$creds = [
    ['email' => 'admin@bengkel.com', 'password' => 'password', 'role' => 'admin'],
    ['email' => 'budi@bengkel.com', 'password' => 'password', 'role' => 'mechanic'],
    ['email' => 'john@customer.com', 'password' => 'password', 'role' => 'customer'],
];

foreach ($creds as $c) {
    echo "Testing login for {$c['email']} ({$c['role']})...\n";
    if (Auth::attempt(['email' => $c['email'], 'password' => $c['password']])) {
        $user = Auth::user();
        echo "SUCCESS: Logged in as {$user->name} (Role: {$user->role})\n";
        
        // Mocking the redirect logic
        $target = match($user->role) {
            'admin' => 'admin.dashboard',
            'mechanic' => 'mechanic.dashboard',
            'customer' => 'customer.dashboard',
            default => '/',
        };
        echo "Redirect target: {$target}\n";
        
        Auth::logout();
    } else {
        echo "FAILED: Could not login with these credentials.\n";
    }
    echo "------------------\n";
}
