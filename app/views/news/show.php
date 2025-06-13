<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2><?= htmlspecialchars($newsItem['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($newsItem['content'])) ?></p>
<small><?= $newsItem['created_at'] ?></small>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
