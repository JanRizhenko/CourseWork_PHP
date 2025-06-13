<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Редагувати бронювання') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
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

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .back-btn {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .booking-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .booking-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .booking-info p {
            margin: 5px 0;
            color: #666;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot {
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            border-color: #667eea;
            background-color: #f0f4ff;
        }

        .time-slot.selected {
            border-color: #667eea;
            background-color: #667eea;
            color: white;
        }

        .time-slot input[type="radio"] {
            display: none;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Редагувати бронювання</h1>
            <p>Змініть деталі бронювання</p>
        </div>
        <a href="/admin/bookings" class="back-btn">← Назад до бронювань</a>
    </div>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <?php if (!empty($booking)): ?>
            <div class="booking-info">
                <h3>Поточна інформація про бронювання</h3>
                <p><strong>Користувач:</strong> <?= htmlspecialchars($booking['user_email'] ?? '') ?></p>
                <p><strong>Створено:</strong> <?= !empty($booking['created_at']) ? date('d.m.Y H:i', strtotime($booking['created_at'])) : '' ?></p>
            </div>

            <form method="POST" action="/admin/bookings/update/<?= htmlspecialchars($booking['id'] ?? '') ?>">
                <div class="form-group">
                    <label for="room_id">Квест</label>
                    <select name="room_id" id="room_id" required>
                        <option value="">Оберіть квест</option>
                        <?php if (!empty($quests) && is_array($quests)): ?>
                            <?php foreach ($quests as $quest): ?>
                                <option value="<?= htmlspecialchars($quest['id'] ?? '') ?>"
                                    <?= ($quest['id'] ?? '') == ($booking['room_id'] ?? '') ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($quest['title'] ?? '') ?>
                                    (<?= htmlspecialchars($quest['price'] ?? 0) ?> грн)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="booking_date">Дата бронювання</label>
                    <input type="date"
                           name="booking_date"
                           id="booking_date"
                           value="<?= htmlspecialchars($booking['booking_date'] ?? '') ?>"
                           min="<?= date('Y-m-d') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Час</label>
                    <div class="time-slots">
                        <?php
                        $timeSlots = ['09:00', '11:00', '13:00', '15:00', '17:00', '19:00'];
                        $currentTimeSlot = $booking['time_slot'] ?? '';
                        ?>
                        <?php foreach ($timeSlots as $slot): ?>
                            <label class="time-slot <?= $slot === $currentTimeSlot ? 'selected' : '' ?>">
                                <input type="radio"
                                       name="time_slot"
                                       value="<?= $slot ?>"
                                    <?= $slot === $currentTimeSlot ? 'checked' : '' ?>
                                       required>
                                <?= $slot ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Оновити бронювання</button>
                    <a href="/admin/bookings" class="btn btn-secondary">Скасувати</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-error">
                <p>Бронювання не знайдено.</p>
                <a href="/admin/bookings" class="btn btn-secondary">Повернутися до списку</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeSlots = document.querySelectorAll('.time-slot');
        const radioButtons = document.querySelectorAll('input[name="time_slot"]');

        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                timeSlots.forEach(s => s.classList.remove('selected'));

                this.classList.add('selected');

                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                timeSlots.forEach(s => s.classList.remove('selected'));
                this.closest('.time-slot').classList.add('selected');
            });
        });
    });
</script>
</body>
</html>