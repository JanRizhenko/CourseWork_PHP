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
            background-color: #f8f9fa;
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
            width: 100% ;
        }

        .btn-primary:hover {
            background-color: #5a6fd8;
        }

        .btn-danger {
            display: flex;
            justify-content: center;
            align-content: center;
            width: 100% ;
            background-color: #dc3545;
            color: white;
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
            font-size: 1.1em;
            color: #333;
            font-weight: 500;
        }

        .created-date {
            font-size: 0.8em;
            color: #666;
        }

        .action-buttons {
            white-space: nowrap;
            min-width: 120px;
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
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            font-size: 0.9em;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        .modal h3 {
            margin-top: 0;
            color: #333;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Управління бронюваннями</h1>
            <p>Переглядайте, редагуйте та скасовуйте бронювання</p>
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
        <?php
        $today = date('Y-m-d');
        $totalBookings = count($bookings);
        $todayBookings = 0;
        $upcomingBookings = 0;
        $pastBookings = 0;

        foreach ($bookings as $booking) {
            $bookingDate = $booking['booking_date'] ?? null;
            if ($bookingDate === $today) {
                $todayBookings++;
            } elseif ($bookingDate > $today) {
                $upcomingBookings++;
            } else {
                $pastBookings++;
            }
        }
        ?>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number"><?= $totalBookings ?></div>
                <div class="stat-label">Всього бронювань</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $todayBookings ?></div>
                <div class="stat-label">Сьогодні</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $upcomingBookings ?></div>
                <div class="stat-label">Майбутні</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $pastBookings ?></div>
                <div class="stat-label">Минулі</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="date-filter">Фільтр по даті:</label>
                    <input type="date" id="date-filter" onchange="filterBookings()">
                </div>
                <div class="filter-group">
                    <label for="status-filter">Статус:</label>
                    <select id="status-filter" onchange="filterBookings()">
                        <option value="">Всі</option>
                        <option value="today">Сьогодні</option>
                        <option value="upcoming">Майбутні</option>
                        <option value="past">Минулі</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search-filter">Пошук:</label>
                    <input type="text" id="search-filter" placeholder="Квест або користувач..." onkeyup="filterBookings()">
                </div>
            </div>
        </div>

        <table class="table" id="bookings-table">
            <thead>
            <tr>
                <th>Квест</th>
                <th>Дата</th>
                <th>Час</th>
                <th>Користувач</th>
                <th>Статус</th>
                <th>Створено</th>
                <th class="action-buttons">Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $booking):
                $bookingDate = $booking['booking_date'] ?? null;
                $createdAt = $booking['created_at'] ?? null;

                $statusClass = 'status-upcoming';
                $statusText = 'Заплановано';
                $statusValue = 'upcoming';

                if ($bookingDate !== null) {
                    if ($bookingDate < $today) {
                        $statusClass = 'status-past';
                        $statusText = 'Минуле';
                        $statusValue = 'past';
                    } elseif ($bookingDate === $today) {
                        $statusClass = 'status-today';
                        $statusText = 'Сьогодні';
                        $statusValue = 'today';
                    }
                }
                ?>
                <tr data-date="<?= htmlspecialchars($bookingDate ?? '') ?>"
                    data-status="<?= $statusValue ?>"
                    data-search="<?= htmlspecialchars(strtolower(($booking['room_name'] ?? '') . ' ' . ($booking['user_email'] ?? ''))) ?>">
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
                        <a href="/admin/bookings/edit/<?= htmlspecialchars($booking['id'] ?? '') ?>"
                           class="btn btn-primary">Редагувати</a>
                        <button onclick="confirmDelete(<?= htmlspecialchars($booking['id'] ?? '') ?>, '<?= htmlspecialchars($booking['room_name'] ?? '') ?>', '<?= htmlspecialchars($bookingDate ?? '') ?>')"
                                class="btn btn-danger">Скасувати</button>
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

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Підтвердження скасування</h3>
        <p id="deleteMessage">Ви впевнені, що хочете скасувати це бронювання?</p>
        <div class="modal-buttons">
            <button onclick="closeModal()" class="btn btn-secondary">Скасувати</button>
            <button onclick="deleteBooking()" class="btn btn-danger">Так, скасувати</button>
        </div>
    </div>
</div>

<script>
    let deleteBookingId = null;

    function confirmDelete(bookingId, questName, date) {
        deleteBookingId = bookingId;
        document.getElementById('deleteMessage').innerHTML =
            `Ви впевнені, що хочете скасувати бронювання<br><strong>${questName}</strong> на <strong>${date}</strong>?`;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
        deleteBookingId = null;
    }

    function deleteBooking() {
        if (deleteBookingId) {
            window.location.href = `/admin/bookings/delete/${deleteBookingId}`;
        }
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    function filterBookings() {
        const dateFilter = document.getElementById('date-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        const searchFilter = document.getElementById('search-filter').value.toLowerCase();
        const rows = document.querySelectorAll('#bookings-table tbody tr');

        rows.forEach(row => {
            let showRow = true;

            if (dateFilter && row.dataset.date !== dateFilter) {
                showRow = false;
            }

            if (statusFilter && row.dataset.status !== statusFilter) {
                showRow = false;
            }

            if (searchFilter && !row.dataset.search.includes(searchFilter)) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }
</script>
</body>
</html>