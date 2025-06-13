<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-container {
            max-width: 1800px;
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

        .header-actions {
            display: flex;
            gap: 15px;
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

        .btn-create {
            width: 100%;
            font-size: 24px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
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

        .btn-primary {
            background-color: #667eea;
            color: white;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .btn-primary:hover {
            background-color: #5a6fd8;
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

        .quest-image {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
        }

        .category-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .category-horror {
            background-color: #ff6b6b;
            color: white;
        }

        .category-adventure {
            background-color: #4ecdc4;
            color: white;
        }

        .category-mystery {
            background-color: #45b7d1;
            color: white;
        }

        .category-action {
            background-color: #96ceb4;
            color: white;
        }

        .difficulty-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .difficulty-easy {
            background-color: #51cf66;
            color: white;
        }

        .difficulty-medium {
            background-color: #ffd43b;
            color: #333;
        }

        .difficulty-hard {
            background-color: #ff6b6b;
            color: white;
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

        .action-buttons {
            white-space: nowrap;
        }

        .quest-title {
            font-weight: bold;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .quest-description {
            max-width: 300px;
            color: #666;
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
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Управління квестами</h1>
            <p>Переглядайте, створюйте та редагуйте квести</p>
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
        <h2>Список квестів</h2>
        <div class="stats">
            <span>Всього квестів: <strong><?= count($quests ?? []) ?></strong></span>
        </div>
    </div>

    <?php if (!empty($quests)): ?>
        <div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
            <a href="/admin/create-quest" class="btn-create">Створити квест</a>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Зображення</th>
                <th>Назва</th>
                <th>Опис</th>
                <th>Категорія</th>
                <th>Складність</th>
                <th>Тривалість</th>
                <th>Місткість</th>
                <th>Ціна</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($quests as $quest): ?>
                <tr>
                    <td>
                        <?php
                        $imagePath = '/images/placeholder.jpeg';
                        if (!empty($quest['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $quest['image'])) {
                            $imagePath = $quest['image'];
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($quest['title']) ?>" class="quest-image">
                    </td>
                    <td>
                        <div class="quest-title" title="<?= htmlspecialchars($quest['title']) ?>">
                            <?= htmlspecialchars($quest['title']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="quest-description" title="<?= htmlspecialchars($quest['description']) ?>">
                            <?= htmlspecialchars($quest['description']) ?>
                        </div>
                    </td>
                    <td>
                                <span class="category-badge category-<?= $quest['category'] ?>">
                                    <?php
                                    $categories = [
                                        'horror' => 'Жахи',
                                        'adventure' => 'Пригоди',
                                        'mystery' => 'Детектив',
                                        'action' => 'Екшн'
                                    ];
                                    echo $categories[$quest['category']] ?? $quest['category'];
                                    ?>
                                </span>
                    </td>
                    <td>
                                <span class="difficulty-badge difficulty-<?= $quest['difficulty'] ?>">
                                    <?php
                                    $difficulties = [
                                        'easy' => 'Легко',
                                        'medium' => 'Середня',
                                        'hard' => 'Важко'
                                    ];
                                    echo $difficulties[$quest['difficulty']] ?? $quest['difficulty'];
                                    ?>
                                </span>
                    </td>
                    <td><?= $quest['duration'] ?> хв</td>
                    <td><?= $quest['capacity'] ?> осіб</td>
                    <td><?= number_format($quest['price'], 0) ?> грн</td>
                    <td class="action-buttons">
                        <a href="/quests/show/<?= $quest['id'] ?>" class="btn btn-info" target="_blank">Переглянути</a>
                        <a href="/admin/edit-quest/<?= $quest['id'] ?>" class="btn btn-primary">Редагувати</a>
                        <a href="/admin/delete-quest/<?= $quest['id'] ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Ви впевнені, що хочете видалити цей квест?')">
                            Видалити
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <h3>Квести не знайдено</h3>
            <p>Станом на зараз немає квестів для відображення.</p>
            <a href="/admin/create-quest" class="btn-create">Створити перший квест</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>