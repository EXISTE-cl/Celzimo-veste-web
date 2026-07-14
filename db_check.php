<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'dffeecfb_celzimo';
$user = 'dffeecfb_celzimo';
$pass = 'Csc170431*';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // List all tables
     $stmt = $pdo->query("SHOW TABLES");
     $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
     
     $matching_tables = [];
     foreach ($tables as $table) {
         $table_lower = strtolower($table);
         if (strpos($table_lower, 'book') !== false || 
             strpos($table_lower, 'reserv') !== false || 
             strpos($table_lower, 'appoint') !== false || 
             strpos($table_lower, 'turn') !== false || 
             strpos($table_lower, 'calend') !== false || 
             strpos($table_lower, 'amelia') !== false ||
             strpos($table_lower, 'latepoint') !== false) {
             
             // Get row count
             $count_stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
             $count = $count_stmt->fetchColumn();
             
             $matching_tables[$table] = [
                 'count' => $count
             ];
         }
     }
     
     echo json_encode([
         'success' => true,
         'tables' => $tables,
         'matching_tables' => $matching_tables
     ], JSON_PRETTY_PRINT);
     
} catch (\PDOException $e) {
     echo json_encode([
         'success' => false,
         'error' => $e->getMessage()
     ], JSON_PRETTY_PRINT);
}
?>
