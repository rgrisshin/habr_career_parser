<?php
// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'aboba';
$username = '1234';
$password = '1234';

// Получаем запрос для поиска из формы
$search_query = $_GET['query'];

try {
    // Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Установка режима обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Подготовленный запрос для поиска по всем полям таблицы
    $stmt = $pdo->prepare("SELECT * FROM aboba 
                           WHERE company = :search_query 
                           OR post = :search_query 
                           OR additional = :search_query 
                           OR other_vacancies = :search_query");

    // Привязка параметров и выполнение запроса
    $stmt->bindParam(':search_query', $search_query);
    $stmt->execute();

    // Вывод результатов
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . "<br>";
        echo "Company: " . $row['company'] . "<br>";
        echo "Post: " . $row['post'] . "<br>";
        echo "Additional: " . $row['additional'] . "<br>";
        echo "Other Vacancies: " . $row['other_vacancies'] . "<br><br>";
    }

} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>