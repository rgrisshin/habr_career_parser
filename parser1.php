<?php
// Подключаем библиотеку PHP Simple HTML DOM Parser
include('dom/simple_html_dom.php');

$host = 'localhost';
$dbname = 'aboba';
$username = '1234';
$password = '1234';

$special = $_GET['query'];

// Устанавливаем ссылку на страницу
$link = 'https://career.habr.com/vacancies?q='.$special.'&s[]=2&s[]=3&s[]=4&s[]=82&s[]=72&s[]=5&s[]=6&s[]=75&s[]=1&s[]=77&s[]=83&s[]=7&s[]=84&s[]=8&s[]=73&s[]=85&s[]=86&s[]=106&type=all';

// Получаем содержимое страницы
$html = file_get_html($link);

// Находим все элементы с классом 'vacancy-card__info'
$vacancyInfo = $html->find('.vacancy-card__info');

// Выводим найденные элементы поочередно в указанном формате
foreach ($vacancyInfo as $info) {
    $company = $info->find('.vacancy-card__company', 0)->plaintext;
    $title = $info->find('.vacancy-card__title', 0)->plaintext;
    $meta = $info->find('.vacancy-card__meta', 0)->plaintext;
    $skills = $info->find('.vacancy-card__skills', 0)->plaintext;

    // Фильтрация данных
    $company = preg_replace('/&#\d{4};/', '', $company);
    $title = preg_replace('/&#\d{4};/', '', $title);
    $meta = preg_replace('/&#\d{4};/', '', $meta);
    $skills = preg_replace('/&#\d{4};/', '', $skills);
    $skills = str_replace('&bull;', ',', $skills);
    $skills = preg_replace('/\s*,/', ',', $skills);
    $company = preg_replace('/\d+\.\d+/', '', $company);

    try {
        // Подключение к базе данных
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        // Установка режима обработки ошибок
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Подготовленный запрос для вставки данных
        $stmt = $pdo->prepare("INSERT INTO aboba (company, post, additional, other_vacancies) 
                               VALUES (:company, :post, :additional, :other_vacancies)
                               ON DUPLICATE KEY UPDATE 
                               company = VALUES(company), 
                               post = VALUES(post), 
                               additional = VALUES(additional), 
                               other_vacancies = VALUES(other_vacancies)");

        // Данные для вставки
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':post', $title);
        $stmt->bindParam(':additional', $meta);
        $stmt->bindParam(':other_vacancies', $skills);
        $stmt->execute();

        echo "Данные успешно внесены в базу данных.";

    } catch(PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }

    echo $company . '<br>';
    echo $title . '<br>';
    echo $meta . '<br>';
    echo $skills . '<br><br>';
}

// Очищаем ресурсы
$html->clear();
unset($html);

?>
