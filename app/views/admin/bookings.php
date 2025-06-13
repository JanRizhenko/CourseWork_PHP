<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Управління бронюваннями') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
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
            font-size: 0.9em;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8em;
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

        .booking-date {
            font-weight: bold;
            color: #333;
        }

        .booking-time {
            color: #667eea;
            font-weight: bold;
        }

        .user-info {
            font-size: 0.9em;
        }

        .quest-info {
            font-size: 1.2em;
            color: #333;
        }

        .created-date {
            font-size: 0.8em;
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

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.7em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-upcoming {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-past {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .status-today {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Управління бронюваннями</h1>
            <p>Переглядайте, редагуйте та видаляйте бронювання</p>
        </div>
        <a href="/admin" class="back-btn">← Назад до панелі</a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($bookings) && is_array($bookings)): ?>
        <table class="table">
            <thead>
            <tr>
                <th>Кімната</th>
                <th>Дата</th>
                <th>Час</th>
                <th>Користувач</th>
                <th>Статус</th>
                <th>Створено</th>
                <th class="action-buttons">Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $today = date('Y-m-d');
            foreach ($bookings as $booking):
                $bookingDate = $booking['booking_date'] ?? null;
                $createdAt = $booking['created_at'] ?? null;

                $statusClass = 'status-upcoming';
                $statusText = 'Заплановано';

                if ($bookingDate !== null) {
                    if ($bookingDate < $today) {
                        $statusClass = 'status-past';
                        $statusText = 'Минуле';
                    } elseif ($bookingDate === $today) {
                        $statusClass = 'status-today';
                        $statusText = 'Сьогодні';
                    }
                }
                ?>
                <tr>
                    <td class="quest-info"><?= htmlspecialchars($booking['room_name'] ?? 'Невідомо') ?></td>
                    <td class="booking-date"><?= htmlspecialchars($bookingDate ?? '') ?></td>
                    <td class="booking-time"><?= htmlspecialchars($booking['time_slot'] ?? '') ?></td>
                    <td class="user-info"><?= htmlspecialchars($booking['user_email'] ?? '') ?></td>
                    <td>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </td>
                    <td class="created-date">
                        <?= !empty($createdAt) ? date('d.m.Y H:i', strtotime($createdAt)) : '' ?>
                    </td>
                    <td class="action-buttons">
                        <a href="/admin/bookings/edit/<?= htmlspecialchars($booking['id'] ?? '') ?>" class="btn btn-primary">Редагувати</a>
                        <a href="/admin/bookings/delete/<?= htmlspecialchars($booking['id'] ?? '') ?>" class="btn btn-danger" onclick="return confirm('Ви впевнені, що хочете скасувати це бронювання?');">Скасувати</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <h3>Немає бронювань</h3>
            <p>Схоже, ще ніхто нічого не забронював.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
