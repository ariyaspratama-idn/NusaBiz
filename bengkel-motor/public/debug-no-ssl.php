<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$user = '2AmTToC83Mx1z21.root';
$pass = 'holmbI1SHHyfER7m';
$db   = 'test';

echo "Testing WITHOUT SSL...\n";
try {
    $pdo = new PDO("mysql:host=$host;port=4000;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "SUCCESS WITHOUT SSL (Unlikely)\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
