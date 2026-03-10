<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('email', 'admin@example.com')->first();
if ($u) {
    $u->role = User::ROLE_SUPER_ADMIN;
    $u->save();
    echo "Admin role updated to SUPER_ADMIN\n";
} else {
    echo "Admin not found\n";
}
