<?php
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

$email = 'kasir@nusabiz.com';
$u = User::where('email', $email)->first();

echo "--- DEBUG INFO ---\n";
if ($u) {
    echo "User Found: " . $u->email . "\n";
    echo "Role: " . $u->role . "\n";
    echo "Password Match (password123): " . (Hash::check('password123', $u->password) ? "YES" : "NO") . "\n";
    
    $k = Karyawan::where('user_id', $u->id)->first();
    if ($k) {
        echo "Karyawan Found: YES\n";
        echo "NIP: " . $k->nip . "\n";
    } else {
        echo "Karyawan Found: NO\n";
    }
} else {
    echo "User NOT FOUND for email: $email\n";
}
echo "--- END DEBUG ---\n";
