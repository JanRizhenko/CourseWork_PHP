<?php require_once __DIR__ . '/../layouts/header.php'; ?>

    <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
    <p class="page-subtitle"><?= htmlspecialchars($welcome) ?></p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid #3498db;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">Квести</h3>
            <p style="color: #7f8c8d; margin-bottom: 15px;">Перегляньте доступні квест-кімнати та їх описи</p>
            <a href="/quest" class="btn">Переглянути квести</a>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid #27ae60;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">Бронювання</h3>
            <p style="color: #7f8c8d; margin-bottom: 15px;">Забронюйте квест на зручний для вас час</p>
            <a href="/booking" class="btn btn-success">Забронювати</a>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid #e74c3c;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">Користувачі</h3>
            <p style="color: #7f8c8d; margin-bottom: 15px;">Управління обліковими записами користувачів</p>
            <a href="/user" class="btn btn-danger">Переглянути</a>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <h2 style="color: #2c3e50; font-size: 1.8rem; margin-bottom: 20px; border-bottom: 3px solid #f39c12; padding-bottom: 10px;">Останні новини</h2>

        <?php if (isset($newsError)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;">
                Помилка завантаження новин: <?= htmlspecialchars($newsError) ?>
            </div>
        <?php elseif (!empty($news)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
                <?php foreach ($news as $article): ?>
                    <article style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 1.3rem;">
                            <?= htmlspecialchars($article['title']) ?>
                        </h3>
                        <p style="color: #7f8c8d; line-height: 1.6; margin-bottom: 15px;">
                            <?= htmlspecialchars(substr($article['content'], 0, 150)) . (strlen($article['content']) > 150 ? '...' : '') ?>
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #95a5a6; font-size: 0.9rem;">
                                <?= date('d.m.Y H:i', strtotime($article['created_at'])) ?>
                            </span>
                            <a href="/news/<?= $article['id'] ?>" class="btn" style="background: #f39c12; padding: 8px 16px; font-size: 0.9rem;">
                                Читати далі
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="/news" class="btn" style="background: #f39c12; padding: 12px 30px;">
                    Переглянути всі новини
                </a>
            </div>
        <?php else: ?>
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <p style="color: #7f8c8d; font-size: 1.1rem;">Новин поки що немає</p>
            </div>
        <?php endif; ?>
    </div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>