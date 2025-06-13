<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-container {
            max-width: 800px;
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

        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
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

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
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
            min-height: 120px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .current-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .help-text {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>Редагувати квест</h1>
            <p>Змініть деталі квесту</p>
        </div>
        <a href="/admin/quests" class="back-btn">← Повернутися до списку</a>
    </div>

    <div class="form-container">
        <form method="POST" action="/admin/update-quest/<?= $quest['id'] ?>">
            <div class="form-group">
                <label for="title">Назва квесту</label>
                <input type="text" id="title" name="title" class="form-control"
                       value="<?= htmlspecialchars($quest['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Опис</label>
                <textarea id="description" name="description" class="form-control" required><?= htmlspecialchars($quest['description']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Категорія</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="horror" <?= $quest['category'] === 'horror' ? 'selected' : '' ?>>Жахи</option>
                        <option value="adventure" <?= $quest['category'] === 'adventure' ? 'selected' : '' ?>>Пригоди</option>
                        <option value="mystery" <?= $quest['category'] === 'mystery' ? 'selected' : '' ?>>Детектив</option>
                        <option value="action" <?= $quest['category'] === 'action' ? 'selected' : '' ?>>Екшн</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="difficulty">Складність</label>
                    <select id="difficulty" name="difficulty" class="form-control" required>
                        <option value="easy" <?= $quest['difficulty'] === 'easy' ? 'selected' : '' ?>>Легко</option>
                        <option value="medium" <?= $quest['difficulty'] === 'medium' ? 'selected' : '' ?>>Середньо</option>
                        <option value="hard" <?= $quest['difficulty'] === 'hard' ? 'selected' : '' ?>>Важко</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duration">Тривалість (хвилини)</label>
                    <input type="number" id="duration" name="duration" class="form-control"
                           value="<?= $quest['duration'] ?>" min="30" max="180" required>
                </div>

                <div class="form-group">
                    <label for="capacity">Місткість (кількість людей)</label>
                    <input type="number" id="capacity" name="capacity" class="form-control"
                           value="<?= $quest['capacity'] ?>" min="1" max="10" required>
                </div>
            </div>

            <div class="form-group">
                <label for="price">Ціна (грн)</label>
                <input type="number" id="price" name="price" class="form-control"
                       value="<?= $quest['price'] ?>" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="image">URL зображення</label>
                <input type="url" id="image" name="image" class="form-control"
                       value="<?= htmlspecialchars($quest['image']) ?>"
                       placeholder="https://example.com/image.jpg">
                <div class="help-text">Введіть повний URL до зображення квесту</div>
                <?php if (!empty($quest['image'])): ?>
                    <img src="<?= htmlspecialchars($quest['image']) ?>" alt="Поточне зображення" class="current-image">
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <a href="/admin/quests" class="btn btn-secondary">Скасувати</a>
                <button type="submit" class="btn btn-primary">Зберегти зміни</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>