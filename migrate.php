<?php
$dsn = getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=test';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$pdo = new PDO($dsn, $user, $pass);
foreach (glob(__DIR__.'/database/migrations/*.sql') as $file) {
    $sql = file_get_contents($file);
    $pdo->exec($sql);
    echo "Executed $file".PHP_EOL;
}
