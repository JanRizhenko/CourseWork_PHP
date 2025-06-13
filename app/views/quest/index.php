<?php
$title = $title ?? "Квести - QuestBooking";
$currentPage = 'quest';

$categoryNames = [
    'horror' => 'Жахи',
    'adventure' => 'Пригоди',
    'mystery' => 'Детектив',
    'action' => 'Екшн'
];

$difficultyNames = [
    'easy' => 'Легкий',
    'medium' => 'Середній',
    'hard' => 'Складний'
];

function formatCapacity($capacity) {
    $n = (int)$capacity;
    $rem10 = $n % 10;
    $rem100 = $n % 100;

    if ($n === 1) {
        return "$n особа";
    } elseif ($rem10 >= 2 && $rem10 <= 4 && ($rem100 < 12 || $rem100 > 14)) {
        return "$n особи";
    } else {
        return "$n осіб";
    }
}
?>

<?php require_once '../app/views/layouts/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">
        <?= isset($selectedCategory) ? $selectedCategory : 'Наші квести' ?>
    </h1>
    <p class="page-subtitle">Виберіть свою пригоду та зануртесь у світ загадок та адреналіну</p>
</div>

<div class="filters-container">
    <form method="GET" action="/quest" class="filters-form">
        <div class="filter-group">
            <label for="category">Категорія:</label>
            <select name="category" id="category">
                <option value="">Всі категорії</option>
                <option value="horror" <?= ($filters['category'] ?? '') === 'horror' ? 'selected' : '' ?>>Жахи</option>
                <option value="adventure" <?= ($filters['category'] ?? '') === 'adventure' ? 'selected' : '' ?>>Пригоди</option>
                <option value="mystery" <?= ($filters['category'] ?? '') === 'mystery' ? 'selected' : '' ?>>Детектив</option>
                <option value="action" <?= ($filters['category'] ?? '') === 'action' ? 'selected' : '' ?>>Екшн</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="capacity">Кількість людей:</label>
            <select name="capacity" id="capacity">
                <option value="">Будь-яка кількість</option>
                <option value="2-3" <?= ($filters['capacity'] ?? '') === '2-3' ? 'selected' : '' ?>>2-3 особи</option>
                <option value="4-5" <?= ($filters['capacity'] ?? '') === '4-5' ? 'selected' : '' ?>>4-5 осіб</option>
                <option value="6+" <?= ($filters['capacity'] ?? '') === '6+' ? 'selected' : '' ?>>6+ осіб</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="priceRange">Ціновий діапазон:</label>
            <select name="priceRange" id="priceRange">
                <option value="">Будь-яка ціна</option>
                <option value="0-500" <?= ($filters['priceRange'] ?? '') === '0-500' ? 'selected' : '' ?>>До 500 ₴</option>
                <option value="500-800" <?= ($filters['priceRange'] ?? '') === '500-800' ? 'selected' : '' ?>>500-800 ₴</option>
                <option value="800-1200" <?= ($filters['priceRange'] ?? '') === '800-1200' ? 'selected' : '' ?>>800-1200 ₴</option>
                <option value="1200+" <?= ($filters['priceRange'] ?? '') === '1200+' ? 'selected' : '' ?>>Від 1200 ₴</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="difficulty">Складність:</label>
            <select name="difficulty" id="difficulty">
                <option value="">Будь-яка складність</option>
                <option value="easy" <?= ($filters['difficulty'] ?? '') === 'easy' ? 'selected' : '' ?>>Легкий</option>
                <option value="medium" <?= ($filters['difficulty'] ?? '') === 'medium' ? 'selected' : '' ?>>Середній</option>
                <option value="hard" <?= ($filters['difficulty'] ?? '') === 'hard' ? 'selected' : '' ?>>Складний</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Застосувати фільтри</button>
            <a href="/quest" class="btn btn-primary">Очистити</a>
        </div>
    </form>
</div>

<?php if (!empty($filters)): ?>
    <div class="active-filters">
        <span>Активні фільтри:</span>
        <?php foreach ($filters as $key => $value): ?>
            <span class="filter-tag">
                <?php
                switch ($key) {
                    case 'category':
                        echo $categoryNames[$value] ?? $value;
                        break;
                    case 'capacity':
                        switch ($value) {
                            case '2-3':
                                echo '2–3 особи';
                                break;
                            case '4-5':
                                echo '4–5 осіб';
                                break;
                            case '6+':
                                echo '6+ осіб';
                                break;
                            default:
                                echo $value;
                        }
                        break;
                    case 'priceRange':
                        echo $value . ' ₴';
                        break;
                    case 'difficulty':
                        echo $difficultyNames[$value] ?? $value;
                        break;
                    default:
                        echo $value;
                }
                ?>
            </span>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="results-info">
    <p>Знайдено квестів: <span class="results-count"><?= count($rooms) ?></span></p>
</div>

<div class="quests-grid">
    <?php if (empty($rooms)): ?>
        <div class="no-results">
            <h3>За вашими критеріями квестів не знайдено</h3>
            <p>Спробуйте змінити фільтри або <a href="/quest">переглянути всі квести</a></p>
        </div>
    <?php else: ?>
        <?php foreach ($rooms as $room): ?>
            <article class="quest-card">
                <div class="quest-image">
                    <?php
                    $imagePath = '/images/placeholder.jpeg';
                    if (!empty($room['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $room['image'])) {
                        $imagePath = $room['image'];
                    }
                    ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($room['title']) ?>" loading="lazy">

                    <div class="quest-overlay">
                        <div class="quest-capacity"><?= htmlspecialchars(formatCapacity($room['capacity'])) ?></div>
                        <div class="quest-price"><?= htmlspecialchars($room['price']) ?> ₴</div>
                    </div>
                    <div class="quest-category-badge <?= $room['category'] ?>">
                        <?= $categoryNames[$room['category'] ?? 'adventure'] ?? 'Пригода' ?>
                    </div>
                </div>
                <div class="quest-content">
                    <h3 class="quest-title"><?= htmlspecialchars($room['title']) ?></h3>
                    <p class="quest-description"><?= htmlspecialchars($room['description']) ?></p>
                    <div class="quest-meta">
                        <span class="quest-difficulty"><?= $difficultyNames[$room['difficulty'] ?? 'medium'] ?? 'Середній' ?></span>
                        <span class="quest-duration"><?= htmlspecialchars($room['duration'] ?? 60) ?> хв</span>
                    </div>
                    <a href="/booking/<?= $room['id'] ?>" class="btn btn-primary btn-full">Забронювати</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
