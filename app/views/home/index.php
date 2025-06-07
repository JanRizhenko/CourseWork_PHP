<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Сайт' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1><?= $welcome ?></h1>

<nav>
    <a href="/rooms/index">Список квест-кімнат</a> |
    <a href="/booking/index">Забронювати</a> |
    <a href="/admin/login">Вхід для адміністратора</a>
</nav>
</body>
</html>
