<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$user = '2AmTToC83Mx1z21.root';
$pass = 'VRYS7OsDKlhIklpa';
$db   = 'test';
$ca   = __DIR__ . '/../cacert.pem';

echo "Testing WITH SSL...\n";
try {
    $pdo = new PDO("mysql:host=$host;port=4000;dbname=$db", $user, $pass, [
        PDO::MYSQL_ATTR_SSL_CA => $ca,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "SUCCESS WITH SSL\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
