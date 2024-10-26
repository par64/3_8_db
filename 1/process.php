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
 
// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Получение данных из формы 
    $username = $_POST['username']; 
    $rating = $_POST['rating']; 
    $comment = $_POST['comment']; 
 
    // Проверка на корректность данных 
    if (empty($username) || empty($rating) || empty($comment)) { 
        die("Все поля обязательны для заполнения."); 
    } 
     // Подготовленный запрос для вставки данных в базу 
     $sql = "INSERT INTO reviews (username, rating, comment) VALUES (:username, :rating, :comment)";
         try { 
            $stmt = $pdo->prepare($sql); 
            $stmt->execute([ 
                ':username' => $username, 
                ':rating' => $rating, 
                ':comment' => $comment 
            ]); 
     
            echo "Отзыв успешно добавлен!"; 
        } catch (PDOException $e) { 
            echo "Error: " . $e->getMessage(); 
        } 
    } 
    ?>