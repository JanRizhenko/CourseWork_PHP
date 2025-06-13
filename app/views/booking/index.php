<?php require_once '../app/views/layouts/header.php'; ?>

    <div class="booking-container">
        <div class="page-header">
            <h1 class="page-title">Бронювання кімнати</h1>
            <h2 class="room-title"><?= h($room['title']) ?></h2>
            <?php if (!empty($room['description'])): ?>
                <p class="room-description"><?= h($room['description']) ?></p>
            <?php endif; ?>
            <div class="quest-info">
                <span class="duration-badge">Тривалість: <?= h($duration) ?> хвилин</span>
            </div>
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

        <div class="booking-section">
            <div class="date-selector">
                <h3>Оберіть дату</h3>
                <form method="get" class="date-form">
                    <div class="form-group">
                        <label for="date">Дата бронювання:</label>
                        <input type="date"
                               name="date"
                               id="date"
                               value="<?= h($date) ?>"
                               min="<?= date('Y-m-d') ?>"
                               max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                               onchange="this.form.submit()"
                               class="date-input">
                    </div>
                </form>
            </div>

            <div class="time-slots-section">
                <h3>Доступні слоти</h3>
                <p class="selected-date">
                    Обрана дата: <strong><?= h(date('d.m.Y', strtotime($date))) ?></strong>
                    (<?= h(['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П\'ятниця', 'Субота'][date('w', strtotime($date))]) ?>)
                </p>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="alert alert-warning">
                        <p>
                            <strong>Увага!</strong> Для бронювання необхідно
                            <a href="/auth/login" class="auth-link">увійти в систему</a> або
                            <a href="/auth/register" class="auth-link">зареєструватися</a>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="time-slots">
                    <?php if (empty($availableSlots)): ?>
                        <div class="alert alert-info">
                            <p>
                                <strong>Інформація:</strong>
                                На обрану дату всі слоти зайняті або недоступні.
                                Спробуйте обрати іншу дату.
                            </p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($availableSlots as $slot): ?>
                            <div class="time-slot">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <?php if ($slot['status'] === 'available'): ?>
                                        <form method="post" action="/booking/store" class="slot-form">
                                            <input type="hidden" name="room_id" value="<?= h($room['id']) ?>">
                                            <input type="hidden" name="date" value="<?= h($date) ?>">
                                            <input type="hidden" name="time_slot" value="<?= h($slot['start_time']) ?>">
                                            <input type="hidden" name="end_time" value="<?= h($slot['end_time']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token'] ?? '') ?>">
                                            <button type="submit" class="slot-button available"
                                                    onclick="return confirm('Підтвердити бронювання на <?= h($slot['formatted_time']) ?>?')">
                                                <?= h($slot['formatted_time']) ?> - Доступно
                                            </button>
                                        </form>
                                    <?php elseif ($slot['status'] === 'booked'): ?>
                                        <button type="button" class="slot-button booked" disabled>
                                            <?= h($slot['formatted_time']) ?> - Зайнято
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="slot-button past" disabled>
                                            <?= h($slot['formatted_time']) ?> - Минуло
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="slot-button <?= $slot['status'] ?>" disabled>
                                        <?= h($slot['formatted_time']) ?> -
                                        <?= $slot['status'] === 'available' ? 'Доступно' :
                                            ($slot['status'] === 'booked' ? 'Зайнято' : 'Минуло') ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="booking-info">
            <h3>Інформація про бронювання</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Робочі години:</strong> 9:00 - 20:00 (щодня)
                </div>
                <div class="info-item">
                    <strong>Тривалість сеансу:</strong> <?= h($duration) ?> хвилин
                </div>
                <div class="info-item">
                    <strong>Скасування:</strong> За 2 години до початку
                </div>
                <div class="info-item">
                    <strong>Обмеження:</strong> Одне бронювання на кімнату на день
                </div>
            </div>
        </div>

        <div class="navigation-actions">
            <a href="/quest" class="btn btn-secondary">
                ← Назад до квестів
            </a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/booking/my" class="btn btn-primary">
                    Мої бронювання →
                </a>
            <?php endif; ?>
        </div>
    </div>

<?php require_once '../app/views/layouts/footer.php'; ?>