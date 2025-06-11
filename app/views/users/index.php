<?php require_once '../app/views/layouts/header.php'; ?>

    <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
    <p class="page-subtitle"><?= htmlspecialchars($welcome) ?></p>

    <div style="margin-bottom: 20px;">
        <a href="/user/add" class="btn btn-success">+ Додати користувача</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Ім'я</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Адміністратор</th>
            <th>Дата реєстрації</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone'] ?? 'Не вказано') ?></td>
                    <td>
                        <?php if ($user['is_admin']): ?>
                            <span style="color: #e74c3c; font-weight: bold;">✓ Так</span>
                        <?php else: ?>
                            <span style="color: #7f8c8d;">✗ Ні</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_at'] ?? 'Не вказано') ?></td>
                    <td>
                        <a href="/user/edit/<?= $user['id'] ?>" class="btn" style="font-size: 0.8rem; padding: 5px 10px;">Редагувати</a>
                        <a href="/user/delete/<?= $user['id'] ?>" class="btn btn-danger" style="font-size: 0.8rem; padding: 5px 10px;" onclick="return confirm('Ви впевнені?')">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; color: #7f8c8d; font-style: italic;">
                    Користувачі не знайдені
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

<?php require_once '../app/views/layouts/footer.php'; ?>