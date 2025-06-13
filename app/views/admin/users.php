<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
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

        .user-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .status-admin {
            background-color: #dc3545;
            color: white;
        }

        .status-user {
            background-color: #28a745;
            color: white;
        }

        .user-date {
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
        }

        .warning-text {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Управління користувачами</h1>
            <p>Переглядайте та блокуйте користувачів</p>
        </div>
        <a href="/admin" class="back-btn">← Назад до панелі</a>
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

    <div class="warning-text">
        <strong>Увага:</strong> Блокування користувача видалить його з системи назавжди. Ця дія незворотна!
    </div>

    <?php if (!empty($users)): ?>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ім'я</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Статус</th>
                <th>Дата реєстрації</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td>
                        <?php if ($user['is_admin']): ?>
                            <span class="user-status status-admin">Адміністратор</span>
                        <?php else: ?>
                            <span class="user-status status-user">Користувач</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="user-date">
                            <?= date('d.m.Y H:i', strtotime($user['registration_date'])) ?>
                        </div>
                    </td>
                    <td class="action-buttons">
                        <?php if (!$user['is_admin']): ?>
                            <a href="/admin/ban-user/<?= $user['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Ви впевнені, що хочете заблокувати користувача <?= htmlspecialchars($user['name']) ?>? Ця дія незворотна!')">
                                Заблокувати
                            </a>
                        <?php else: ?>
                            <span style="color: #666; font-style: italic;">Адміністратор</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <h3>Користувачі не знайдено</h3>
            <p>Станом на зараз немає користувачів для відображення.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>