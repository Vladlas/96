<?php
require __DIR__ . '/bootstrap.php';

pageHeader('Админ-панель музея', 'admin');
?>

<section class="panel">
    <h2>Текущее состояние</h2>
    <div class="stats">
        <div><small>Заметки</small><strong><?= count($notes) ?></strong></div>
        <div><small>События</small><strong><?= count($calendarEvents) ?></strong></div>
    </div>
</section>

<section class="split">
    <form method="post" class="panel form-block">
        <input type="hidden" name="action" value="add_note">
        <input type="hidden" name="redirect_to" value="admin.php">
        <h2>Добавить заметку</h2>
        <select name="hall_id" required>
            <option value="history">Зал истории</option>
            <option value="science">Зал науки</option>
            <option value="people">Зал людей</option>
        </select>
        <input name="title" placeholder="Заголовок" required>
        <input name="short" placeholder="Краткое описание" required>
        <textarea name="content" rows="5" placeholder="Полный текст заметки" required></textarea>
        <button class="btn" type="submit">Сохранить заметку</button>
    </form>

    <form method="post" class="panel form-block">
        <input type="hidden" name="action" value="add_event">
        <input type="hidden" name="redirect_to" value="admin.php">
        <h2>Добавить событие</h2>
        <input name="date" placeholder="Дата (например, 4 июня)" required>
        <input name="title" placeholder="Название события" required>
        <button class="btn" type="submit">Добавить событие</button>
    </form>
</section>

<?php pageFooter(); ?>
