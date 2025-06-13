<?php require_once '../app/views/layouts/header.php'; ?>

    <div class="booking-container">
        <div class="page-header">
            <h1 class="page-title">Мої бронювання</h1>
            <p class="page-subtitle">Тут ви можете переглянути всі ваші активні та недавні бронювання</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= h($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= h($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php
        $visibleBookings = [];
        $oneDayInSeconds = 1 * 60 * 60;
        $currentTimestamp = time();

        foreach ($bookings as $booking) {
            $bookingDateTime = $booking['booking_date'] . ' ' . $booking['time_slot'];
            $bookingTimestamp = strtotime($bookingDateTime);

            if ($bookingTimestamp >= $currentTimestamp ||
                ($currentTimestamp - $bookingTimestamp) <= $oneDayInSeconds) {
                $visibleBookings[] = $booking;
            }
        }
        ?>

        <?php if (empty($visibleBookings)): ?>
            <div class="alert alert-info">
                <p>У вас немає активних або недавніх бронювань. <a href="/quest" class="auth-link">Перейти до квестів</a></p>
                <?php if (!empty($bookings)): ?>
                    <small>Старі бронювання автоматично приховуються через день після завершення.</small>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bookings-list">
                <table>
                    <thead>
                    <tr>
                        <th>Кімната</th>
                        <th>Дата</th>
                        <th>Час</th>
                        <th>Статус</th>
                        <th>Дії</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($visibleBookings as $booking): ?>
                        <?php
                        $bookingDateTime = $booking['booking_date'] . ' ' . $booking['time_slot'];
                        $bookingTimestamp = strtotime($bookingDateTime);
                        $currentTimestamp = time();
                        $timeDiff = $bookingTimestamp - $currentTimestamp;
                        $isPast = $bookingTimestamp < $currentTimestamp;
                        $canCancel = $timeDiff > 3600;

                        $isRecentlyCompleted = $isPast && (($currentTimestamp - $bookingTimestamp) <= $oneDayInSeconds);
                        ?>
                        <tr class="<?= $isRecentlyCompleted ? 'recently-completed' : '' ?>">
                            <td>
                                <strong><?= h($booking['room_title']) ?></strong>
                                <?php if (!empty($booking['room_description'])): ?>
                                    <p class="room-description"><?= h($booking['room_description']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= h(date('d.m.Y', strtotime($booking['booking_date']))) ?><br>
                                <small><?= h(['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П\'ятниця', 'Субота'][date('w', strtotime($booking['booking_date']))]) ?></small>
                            </td>
                            <td><?= h(date('H:i', strtotime($booking['time_slot']))) ?></td>
                            <td>
                                <?php if ($isPast): ?>
                                    <?php if ($isRecentlyCompleted): ?>
                                        <span class="status-badge recently-completed">Недавно завершено</span>
                                    <?php else: ?>
                                        <span class="status-badge past">Завершено</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="status-badge active">Активне</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$isPast && $canCancel): ?>
                                    <form method="post" action="/booking/cancel" class="cancel-form">
                                        <input type="hidden" name="booking_id" value="<?= h($booking['id']) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token'] ?? '') ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете скасувати це бронювання?')">
                                            Скасувати
                                        </button>
                                    </form>
                                <?php elseif (!$isPast && !$canCancel): ?>
                                    <small>Скасування недоступне</small>
                                <?php elseif ($isRecentlyCompleted): ?>
                                    <small class="recently-completed-text">Недавно завершено</small>
                                <?php else: ?>
                                    <small>-</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($visibleBookings) < count($bookings)): ?>
                <div class="alert alert-info hidden-bookings-notice">
                    <small>
                        Приховано <?= count($bookings) - count($visibleBookings) ?> старих бронювань
                        (старші 1 години після завершення). Вони залишаються в статистиці вашого профілю.
                    </small>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="navigation-actions">
            <a href="/quest" class="btn btn-secondary">
                ← Назад до квестів
            </a>
            <a href="/user/profile" class="btn btn-outline">
                Переглянути статистику
            </a>
        </div>
    </div>

<?php require_once '../app/views/layouts/footer.php'; ?>

<style>
    .bookings-list {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 32px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 16px 20px;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
    }

    th {
        background: var(--gray-100);
        font-weight: 600;
        color: var(--gray-800);
    }

    tr:hover td {
        background-color: var(--gray-50);
    }

    tr.recently-completed {
        background-color: rgba(249, 250, 251, 0.7);
    }

    tr.recently-completed:hover td {
        background-color: rgba(243, 244, 246, 0.8);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: var(--success-color);
        color: white;
    }

    .status-badge.past {
        background: var(--gray-300);
        color: var(--gray-700);
    }

    .status-badge.recently-completed {
        background: var(--warning-color, #f59e0b);
        color: white;
    }

    .recently-completed-text {
        color: var(--warning-color, #f59e0b);
        font-style: italic;
    }

    .room-description {
        color: var(--gray-600);
        font-size: 0.9rem;
        margin-top: 4px;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
        min-height: auto;
    }

    .hidden-bookings-notice {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 12px 16px;
        margin-bottom: 20px;
    }

    .hidden-bookings-notice small {
        color: var(--gray-600);
        display: block;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline:hover {
        background: var(--primary-color);
        color: white;
    }

    @media (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
        }

        th, td {
            padding: 12px 16px;
        }

        .navigation-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .navigation-actions .btn {
            text-align: center;
        }
    }
</style>
