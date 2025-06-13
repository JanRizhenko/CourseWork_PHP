<?php require_once __DIR__ . '/../layouts/header.php'; ?>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-cover">
                <div class="profile-cover-overlay"></div>
            </div>

            <div class="profile-main">
                <div class="profile-avatar-section">
                    <div class="profile-avatar">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&size=120&background=6366f1&color=ffffff&bold=true" alt="Profile Avatar">
                    </div>
                    <div class="profile-basic-info">
                        <h1 class="profile-name"><?= htmlspecialchars($user['name'] ?? 'Користувач') ?></h1>
                        <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                        <div class="profile-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= isset($bookingsCount) ? htmlspecialchars($bookingsCount) : '0' ?></span>
                                <span class="stat-label">Бронювань</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= date('d.m.Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                                <span class="stat-label">Дата реєстрації</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button type="button" class="btn profile-edit-btn" onclick="toggleEditMode()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4L18.5 2.5z"></path>
                            </svg>
                            Редагувати профіль
                        </button>
                        <button type="button" class="btn btn-danger profile-delete-btn" onclick="showDeleteModal()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3,6 5,6 21,6"></polyline>
                                <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Видалити акаунт
                        </button>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success profile-alert" id="success-message">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="close-alert" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger profile-alert">
                        <ul class="profile-error-list">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="profile-info-grid">
                    <div class="info-card">
                        <div class="info-card-header">
                            <h3>Особиста інформація</h3>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-label">Повне ім'я:</span>
                                <span class="info-value" id="display-name"><?= htmlspecialchars($user['name'] ?? '') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email адреса:</span>
                                <span class="info-value" id="display-email"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Статус акаунту:</span>
                                <span class="info-value status-active">
                                <span class="status-dot"></span>
                                Активний
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-card-header">
                            <h3>Статистика</h3>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-label">Всього бронювань:</span>
                                <span class="info-value highlight"><?= isset($bookingsCount) ? htmlspecialchars($bookingsCount) : '0' ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Останній вхід:</span>
                                <span class="info-value"><?= date('d.m.Y H:i') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Учасник з:</span>
                                <span class="info-value"><?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-edit-form" id="editForm" style="display: none;">
                <div class="edit-form-header">
                    <h3>Редагувати профіль</h3>
                    <button type="button" class="btn-close-edit" onclick="toggleEditMode()">×</button>
                </div>

                <form method="POST" action="/user/profile" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Ім'я</label>
                            <input type="text" class="form-input" id="name" name="name"
                                   value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-input" id="email" name="email"
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Номер телефону</label>
                            <input type="tel" class="form-input" id="phone" name="phone"
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                            <small class="form-help">Формат: +380XXXXXXXXX</small>
                        </div>
                    </div>

                    <div class="form-divider">
                        <span>Зміна паролю (необов'язково)</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="current_password" class="form-label">Поточний пароль</label>
                            <input type="password" class="form-input" id="current_password" name="current_password">
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="form-label">Новий пароль</label>
                            <input type="password" class="form-input" id="new_password" name="new_password">
                            <small class="form-help">Залиште порожнім, щоб не змінювати</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Скасувати</button>
                        <button type="submit" class="btn profile-edit-btn">Зберегти зміни</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal-overlay" id="deleteModal" style="display: none;">
            <div class="profile-modal-dialog">
                <div class="profile-modal-content">
                    <div class="profile-modal-header">
                        <h5 class="profile-modal-title">Підтвердження видалення акаунту</h5>
                        <button type="button" class="profile-modal-close" onclick="hideDeleteModal()">
                            &times;
                        </button>
                    </div>
                    <div class="profile-modal-body">
                        <div class="warning-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                <path d="M12 9v4"></path>
                                <path d="m12 17 .01 0"></path>
                            </svg>
                        </div>
                        <p class="profile-modal-text">
                            Ви впевнені, що хочете видалити свій акаунт?
                            <br><strong>Ця дія не може бути скасована.</strong>
                            <br><br>Всі ваші дані та бронювання будуть видалені назавжди.
                        </p>
                    </div>
                    <div class="profile-modal-footer">
                        <button type="button" class="profile-modal-cancel" onclick="hideDeleteModal()">Скасувати</button>
                        <form action="/auth/delete" method="POST" style="display: inline;">
                            <button type="submit" class="profile-modal-confirm">Видалити акаунт</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleEditMode() {
                const editForm = document.getElementById('editForm');
                const profileDetails = document.querySelector('.profile-details');

                if (editForm.style.display === 'none' || editForm.style.display === '') {
                    editForm.style.display = 'block';
                    profileDetails.style.display = 'none';
                } else {
                    document.getElementById('display-name').textContent = document.getElementById('name').value;
                    document.getElementById('display-email').textContent = document.getElementById('email').value;
                    document.getElementById('display-phone').textContent = document.getElementById('phone').value || 'Не вказано';

                    editForm.style.display = 'none';
                    profileDetails.style.display = 'block';
                }
            }

            function showDeleteModal() {
                document.getElementById('deleteModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function hideDeleteModal() {
                document.getElementById('deleteModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideDeleteModal();
                }
            });

            document.getElementById('phone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0 && !value.startsWith('+')) {
                    value = '+380' + value.substring(3);
                }
                e.target.value = value.substring(0, 13);
            });
        </script>
    </div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>