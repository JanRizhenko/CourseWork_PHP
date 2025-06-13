<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
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
            text-align: center;
        }

        .admin-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-nav-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .admin-nav-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .admin-nav-item i {
            font-size: 2em;
            margin-bottom: 10px;
            color: #667eea;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9em;
        }

        .recent-activity {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .recent-activity h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-date {
            color: #666;
            font-size: 0.8em;
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
        }

        .btn-primary:hover {
            background-color: #5a6fd8;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.25);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h1>Панель адміністрування</h1>
        <p>Керуйте сайтом QuestBooking</p>
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

    <div class="admin-nav">
        <a href="/admin/quests" class="admin-nav-item">
            <div></div>
            <h3>Квести</h3>
            <p>Керування квестами</p>
        </a>
        <a href="/admin/news" class="admin-nav-item">
            <div></div>
            <h3>Новини</h3>
            <p>Керування новинами</p>
        </a>
        <a href="/admin/users" class="admin-nav-item">
            <div></div>
            <h3>Користувачі</h3>
            <p>Керування користувачами</p>
        </a>
        <a href="/admin/bookings" class="admin-nav-item">
            <div></div>
            <h3>Бронювання</h3>
            <p>Керування бронюваннями</p>
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $stats['total_users'] ?></div>
            <div class="stat-label">Всього користувачів</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['total_quests'] ?></div>
            <div class="stat-label">Всього квестів</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['total_news'] ?></div>
            <div class="stat-label">Всього новин</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['total_bookings'] ?></div>
            <div class="stat-label">Всього бронювань</div>
        </div>
    </div>

    <div class="recent-activity">
        <h3>Останні бронювання</h3>
        <?php if (!empty($stats['recent_bookings'])): ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Користувач</th>
                    <th>Номер телефону</th>
                    <th>Квест</th>
                    <th>Дата</th>
                    <th>Час</th>
                    <th>Створено</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($stats['recent_bookings'] as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['user_name']) ?></td>
                        <td><?= htmlspecialchars($booking['user_phone'] ?? 'Не вказано') ?></td>
                        <td><?= htmlspecialchars($booking['room_title']) ?></td>
                        <td><?= date('d.m.Y', strtotime($booking['booking_date'])) ?></td>
                        <td><?= date('H:i', strtotime($booking['time_slot'])) ?></td>
                        <td class="activity-date"><?= date('d.m.Y H:i', strtotime($booking['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Немає останніх бронювань</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>