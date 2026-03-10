<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$db   = 'test';
$user = '2AmTToC83Mx1z21.root';
$pass = 'holmbI1SHHyfER7m';
$ca = __DIR__ . '/../cacert.pem';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::MYSQL_ATTR_SSL_CA => $ca,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $stmt = $pdo->query("SELECT email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total users: " . count($users) . "\n";
    foreach($users as $u) {
        echo "- {$u['email']} ({$u['role']})\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
