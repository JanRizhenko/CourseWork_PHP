<?php require_once '../app/views/layouts/header.php'; ?>

    <section class="main-content">
        <header>
            <h2 class="page-title"><?= htmlspecialchars($title) ?></h2>
        </header>

        <?php if (!empty($newsList)) : ?>
            <ul class="news-list">
                <?php foreach ($newsList as $news) : ?>
                    <li class="news-item">
                        <article>
                            <h3>
                                <a href="/news/show/<?= $news['id'] ?>">
                                    <?= htmlspecialchars($news['title']) ?>
                                </a>
                            </h3>
                            <p>
                                <?= nl2br(htmlspecialchars(mb_substr($news['content'], 0, 100))) ?>...
                            </p>
                            <time class="news-date" datetime="<?= htmlspecialchars($news['created_at']) ?>">
                                <?= date("d M Y", strtotime($news['created_at'])) ?>
                            </time>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Наразі немає новин для відображення.</p>
        <?php endif; ?>
    </section>

<?php require_once '../app/views/layouts/footer.php'; ?>