<?php
// Подключение к базе данных 
$dsn = "mysql:host=localhost;dbname=my_database";
$username = "root";
$password = "1";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $reason = $_POST['reason'];

     // Проверка на корректность данных 
     if (empty($order_id) || empty($reason)) { 
        die("Все поля обязательны для заполнения."); 
    } 
    // Готовим запрос для вставки данных в базу
    $sql = "INSERT INTO cancellations (order_id, reason) VALUES (:order_id, :reason)";
    
    try {
        
        $stmt = $pdo->prepare($sql); 
        $stmt->execute([ 
            ':order_id' => $order_id, 
            ':reason' => $reason
        ]);
        echo "Заказ #$order_id успешно отменен!";
    } catch (PDOException $e) { 
        echo "Error: " . $e->getMessage(); 
    } 
} 
