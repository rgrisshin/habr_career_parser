<!DOCTYPE html>
<html>
<head>
    <title>Поиск в базе данных</title>
</head>
<body>
    <h2>Поиск в базе данных</h2>
    <form method="get" action="search.php">
        <input type="text" name="query" placeholder="Введите запрос для поиска">
        <input type="submit" value="Поиск">
    </form>
</body>
</html>
<br><br>

<?php
// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'aboba';
$username = '1234';
$password = '1234';

try {
    // Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Установка режима обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Подготовленный запрос для выборки всех строк из таблицы
    $stmt = $pdo->prepare("SELECT * FROM aboba");

    // Выполнение запроса
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
