<?php require_once __DIR__ . '/../layouts/header.php'; ?>

    <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>


    <div style="margin-top: 20px;">
        <h2 style="color: #2c3e50; font-size: 1.8rem; margin-bottom: 20px; border-bottom: 3px solid #f39c12; padding-bottom: 10px;">Останні новини</h2>

        <?php if (isset($newsError)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;">
                Помилка завантаження новин: <?= htmlspecialchars($newsError) ?>
            </div>
        <?php elseif (!empty($news)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
                <?php foreach ($news as $article): ?>
                    <article style="
                                        background: white;
                                        padding: 30px;
                                        border-radius: 12px;
                                        box-shadow: 0 6px 10px rgba(0,0,0,0.1);
                                        min-height: 350px;
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: space-between;
                                    ">
                        <div>
                            <h3 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.4rem;">
                                <?= htmlspecialchars($article['title']) ?>
                            </h3>
                            <p style="color: #7f8c8d; line-height: 1.8; font-size: 1rem; margin-bottom: 25px;">
                                <?= htmlspecialchars(substr($article['content'], 0, 550)) . (strlen($article['content']) > 250 ? '...' : '') ?>
                            </p>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #95a5a6; font-size: 0.9rem;">
                                <?= date('d.m.Y H:i', strtotime($article['created_at'])) ?>
                            </span>
                            <a href="/news/show/<?= $article['id'] ?>" class="btn" style="background: #f39c12; padding: 10px 18px; font-size: 0.9rem;">
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