<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$db   = 'test';
$user = '2AmTToC83Mx1z21.root';
$pass = 'VRYS7OsDKlhIklpa';
$charset = 'utf8mb4';
$ca = __DIR__ . '/../cacert.pem';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_SSL_CA       => $ca,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connection success!";
} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage();
}
