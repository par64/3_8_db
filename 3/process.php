<!-- Оставлю для себя много подсказок, чтоб не забыть в будущем,
надеюсь они не помешают при проверке -->

<?php
//Настройки для подключения БД
$host = 'localhost';
$db = 'my_database';
$username = "root";
$password = "1";
$charset = 'utf8mb4';

//Конструктор объекта PDO принимает 4 параметра:
//1-Настройки подключения к БД
//2-Логин пользователя БД
//3-Пароль пользователя БД
//4-Доп параметры (необязательные, но оч. важные)
//Чтобы все эти параметры при создании PDO передать,
//для удобства сохраняют их в переменные (логин и пароль уже есть) 

// 1-Строка настроек для подключения к БД через PDO 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//4-массив доп.параметров
//(режим обработки ошибок и метод формирования выборки при получении результата запроса)
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

//Создаем объект PDO ("подключение к БД")
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}


// Проверяем, что запрос был отправлен методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_name = $_POST['name'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Проверка на корректность данных 
    if (empty($user_name) || empty($rating) || empty($comment)) { 
        die("Все поля обязательны для заполнения."); 
    } 
     // Подготовленный запрос для вставки данных в базу 
     $sql = "INSERT INTO reviews (username, rating, comment) VALUES (:user_name, :rating, :comment)";
         try {
        //С помощью метода prepare() объекта PDO создаем объект PDOStatement
        //(подготовленное выражение, для добавления данных в БД)
        //но чтобы вместо значений, передать на сервер названия параметров,
        //для этого перед предполагаемыми значениями ставятся двоеточия. 
            $stmt = $pdo->prepare($sql);
        //теперь можно безопасно заполнять параметры запроса значениями
        //и выполнять запрос (метод execute() для команды INSERT возвращает число добавленных строк.)
            $stmt->execute([ 
                ':user_name' => $user_name, 
                ':rating' => $rating, 
                ':comment' => $comment 
            ]); 
     
            echo "Отзыв успешно добавлен!"; 
        } catch (PDOException $e) { 
            echo "Error: " . $e->getMessage(); 
        } 

    $sql = "SELECT * FROM reviews";
    //Тут метод >query() также создает объект PDOStatement, который
    //в данном случае, представляет выборку данных из БД
    $stmt = $pdo->query($sql);
    //формируем из выборки массив данных 
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //Вывод данных на страницу
    foreach ($reviews as $review) { 
        echo "<p><strong>{$review['username']}</strong>: {$review['comment']} (Рейтинг: 
    {$review['rating']})</p>"; 
    }
}
