<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-create {
            font-size: 18px;
            background: #28a745;
            color: white;
            padding: 12px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-create::before {
            content: '+';
            font-size: 1.2em;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            font-weight: bold;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .news-title {
            font-weight: bold;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .news-content {
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #666;
        }

        .news-date {
            font-size: 0.9em;
            color: #666;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .empty-state h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 20px;
        }

        .content-actions {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .content-actions h2 {
            margin: 0;
            color: #333;
        }

        .stats {
            display: flex;
            gap: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Управління новинами</h1>
            <p>Переглядайте, створюйте та видаляйте новини</p>
        </div>
        <div class="header-actions">
            <a href="/admin" class="back-btn">← Назад до панелі</a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="content-actions">
        <h2>Список новин</h2>
        <div class="header-actions">
            <div class="stats">
                <span>Всього новин: <strong><?= count($news ?? []) ?></strong></span>
            </div>
        </div>
    </div>

    <?php if (!empty($news)): ?>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Заголовок</th>
                <th>Контент</th>
                <th>Дата створення</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            <a href="/admin/create-news" class="btn-create">Створити новину</a>
            <?php foreach ($news as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td>
                        <div class="news-title" title="<?= htmlspecialchars($item['title']) ?>">
                            <?= htmlspecialchars($item['title']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="news-content" title="<?= htmlspecialchars($item['content']) ?>">
                            <?= htmlspecialchars($item['content']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="news-date">
                            <?= date('d.m.Y H:i', strtotime($item['created_at'])) ?>
                        </div>
                    </td>
                    <td class="action-buttons">
                        <a href="/news/show/<?= $item['id'] ?>" class="btn btn-info" target="_blank">Переглянути</a>
                        <a href="/admin/edit-news/<?= $item['id'] ?>" class="btn btn-warning">Редагувати</a>
                        <a href="/admin/delete-news/<?= $item['id'] ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Ви впевнені, що хочете видалити цю новину?')">
                            Видалити
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <h3>Новини не знайдено</h3>
            <p>Станом на зараз немає новин для відображення.</p>
            <a href="/admin/create-news" class="btn-create">Створити першу новину</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>