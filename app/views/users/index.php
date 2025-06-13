<?php require_once '../app/views/layouts/header.php'; ?>

    <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
    <p><?= htmlspecialchars($welcome) ?></p>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Ім'я</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Адміністратор</th>
            <th>Дата реєстрації</th>
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