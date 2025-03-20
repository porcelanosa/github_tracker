<?php
$host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost');
$dbName = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'default_db');
$dbUser = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root');
$dbPassword = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');

$dbConfig = [
  'class' => 'yii\db\Connection',
  'dsn' => "mysql:host=$host;dbname=$dbName",
  'username' => $dbUser,
  'password' => $dbPassword,
  'charset' => 'utf8mb4',
];
return $dbConfig;