<?php
session_start();

require __DIR__ . '/data.php';

$hallIds = array_column($halls, 'id');
$activeHall = $_GET['hall'] ?? 'main';
if (!in_array($activeHall, $hallIds, true)) {
    $activeHall = 'main';
}

if (!isset($_SESSION['notes'])) {
    $_SESSION['notes'] = $defaultNotes;
}
if (!isset($_SESSION['calendar'])) {
    $_SESSION['calendar'] = $defaultCalendar;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_note') {
        $hallId = $_POST['hall_id'] ?? 'history';
        $title = trim($_POST['title'] ?? '');
        $short = trim($_POST['short'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (in_array($hallId, ['history', 'science', 'people'], true) && $title !== '' && $short !== '' && $content !== '') {
            array_unshift($_SESSION['notes'], [
                'id' => 'n-' . time() . '-' . random_int(100, 999),
                'hallId' => $hallId,
                'title' => $title,
                'short' => $short,
                'content' => $content,
            ]);
        }
    }

    if ($action === 'add_event') {
        $date = trim($_POST['date'] ?? '');
        $title = trim($_POST['title'] ?? '');

        if ($date !== '' && $title !== '') {
            array_unshift($_SESSION['calendar'], [
                'id' => 'c-' . time() . '-' . random_int(100, 999),
                'date' => $date,
                'title' => $title,
            ]);
        }
    }

    $redirectHall = $_POST['redirect_hall'] ?? 'main';
    if (!in_array($redirectHall, $hallIds, true)) {
        $redirectHall = 'main';
    }
    header('Location: ?hall=' . urlencode($redirectHall) . '&admin=1');
    exit;
}

$notes = $_SESSION['notes'];
$calendarEvents = $_SESSION['calendar'];

$section = $museumContent[0];
foreach ($museumContent as $entry) {
    if ($entry['hallId'] === $activeHall) {
        $section = $entry;
        break;
    }
}

$visibleNotes = array_values(array_filter($notes, static function (array $note) use ($activeHall): bool {
    return $activeHall === 'main' || $note['hallId'] === $activeHall;
}));

$selectedNoteId = $_GET['note'] ?? null;
$selectedNote = null;
if ($selectedNoteId) {
    foreach ($notes as $note) {
        if ($note['id'] === $selectedNoteId) {
            $selectedNote = $note;
            break;
        }
    }
}

$showAdmin = isset($_GET['admin']) && $_GET['admin'] === '1';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Виртуальный музей кафедры 96</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="page">
    <main class="container">
        <header class="card hero">
            <div class="hero-top">
                <div>
                    <p class="eyebrow">Виртуальный музей кафедры 96</p>
                    <h1>Кафедральная экспозиция и летопись</h1>
                    <p class="lead">Материалы кафедры: символика, учебный процесс, выставочная деятельность и кураторские заметки в едином цифровом пространстве.</p>
                </div>
                <a class="btn" href="?hall=<?= e($activeHall) ?>&admin=1">Админ-панель</a>
            </div>
            <div class="profile">
                <strong>Кафедральный профиль.</strong>
                <p>Раздел дополнен символикой и фотографиями, предоставленными для наполнения виртуального музея.</p>
            </div>
        </header>

        <nav class="hall-nav">
            <?php foreach ($halls as $hall): ?>
                <a class="pill <?= $activeHall === $hall['id'] ? 'active' : '' ?>" href="?hall=<?= e($hall['id']) ?>"><?= e($hall['title']) ?></a>
            <?php endforeach; ?>
        </nav>

        <?php if ($activeHall === 'main'): ?>
            <section class="two-col">
                <article class="card">
                    <p class="eyebrow">Центральная витрина</p>
                    <h2><?= e($section['heading']) ?></h2>
                    <p><?= e($section['description']) ?></p>
                    <div class="chips">
                        <?php foreach ($section['items'] as $item): ?>
                            <span class="chip"><?= e($item) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="links">
                        <?php foreach ($halls as $hall): ?>
                            <?php if ($hall['id'] === 'main') {
                                continue;
                            } ?>
                            <a href="?hall=<?= e($hall['id']) ?>" class="btn ghost"><?= e($hall['title']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </article>
                <aside class="card">
                    <h3>Календарь кафедры</h3>
                    <?php foreach (array_slice($calendarEvents, 0, 3) as $event): ?>
                        <div class="event">
                            <small><?= e($event['date']) ?></small>
                            <p><?= e($event['title']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </aside>
            </section>
            <?php renderGallery('Кафедральная символика', 'Эмблемы кафедры и связанные знаки из предоставленного набора материалов', $departmentHeraldry, true); ?>
        <?php else: ?>
            <section class="card">
                <h2><?= e($section['heading']) ?></h2>
                <p><?= e($section['description']) ?></p>
                <div class="chips">
                    <?php foreach ($section['items'] as $item): ?>
                        <span class="chip"><?= e($item) ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php renderGallery('Фотолетопись кафедры', 'Учебные занятия, выездные этапы и демонстрация разработок на профильных площадках', $departmentGallery); ?>

        <section class="card">
            <div class="row-head">
                <h3>Быстрые маршруты</h3>
                <span><?= $activeHall === 'main' ? 'все залы' : 'текущий фокус' ?></span>
            </div>
            <div class="route-grid">
                <?php foreach ($curatorRoutes as $route): ?>
                    <a href="?hall=<?= e($route['hallIds'][0]) ?>" class="route-item">
                        <div>
                            <strong><?= e($route['title']) ?></strong>
                            <small><?= e($route['duration']) ?></small>
                        </div>
                        <span>→</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="card">
            <h3>Кураторские заметки</h3>
            <div class="note-grid">
                <?php foreach ($visibleNotes as $note): ?>
                    <a class="note" href="?hall=<?= e($activeHall) ?>&note=<?= e($note['id']) ?>">
                        <strong><?= e($note['title']) ?></strong>
                        <p><?= e($note['short']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<?php if ($selectedNote): ?>
    <div class="modal" onclick="window.location='?hall=<?= e($activeHall) ?>'">
        <article class="modal-card" onclick="event.stopPropagation()">
            <div class="row-head">
                <h3><?= e($selectedNote['title']) ?></h3>
                <a class="close" href="?hall=<?= e($activeHall) ?>">×</a>
            </div>
            <p><?= e($selectedNote['content']) ?></p>
        </article>
    </div>
<?php endif; ?>

<?php if ($showAdmin): ?>
    <div class="modal" onclick="window.location='?hall=<?= e($activeHall) ?>'">
        <aside class="panel" onclick="event.stopPropagation()">
            <div class="row-head">
                <h3>Админ-панель музея</h3>
                <a class="close" href="?hall=<?= e($activeHall) ?>">×</a>
            </div>

            <div class="stats">
                <div><small>Заметки</small><strong><?= count($notes) ?></strong></div>
                <div><small>События</small><strong><?= count($calendarEvents) ?></strong></div>
            </div>

            <form method="post" class="form">
                <input type="hidden" name="action" value="add_note">
                <input type="hidden" name="redirect_hall" value="<?= e($activeHall) ?>">
                <h4>Добавить заметку</h4>
                <select name="hall_id" required>
                    <?php foreach ($halls as $hall): ?>
                        <?php if ($hall['id'] === 'main') {
                            continue;
                        } ?>
                        <option value="<?= e($hall['id']) ?>"><?= e($hall['title']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input name="title" placeholder="Заголовок" required>
                <input name="short" placeholder="Краткое описание" required>
                <textarea name="content" placeholder="Полный текст заметки" rows="4" required></textarea>
                <button class="btn dark" type="submit">Сохранить заметку</button>
            </form>

            <form method="post" class="form">
                <input type="hidden" name="action" value="add_event">
                <input type="hidden" name="redirect_hall" value="<?= e($activeHall) ?>">
                <h4>Добавить событие</h4>
                <input name="date" placeholder="Дата (например, 4 июня)" required>
                <input name="title" placeholder="Название события" required>
                <button class="btn dark" type="submit">Добавить событие</button>
            </form>
        </aside>
    </div>
<?php endif; ?>
</body>
</html>

<?php
function renderGallery(string $title, string $subtitle, array $items, bool $compact = false): void
{
    $class = $compact ? 'gallery compact' : 'gallery';
    echo '<section class="card">';
    echo '<h3>' . e($title) . '</h3>';
    echo '<p class="subtitle">' . e($subtitle) . '</p>';
    echo '<div class="' . $class . '">';
    foreach ($items as $item) {
        echo '<article class="gallery-item">';
        echo '<img src="' . e($item['image']) . '" alt="' . e($item['title']) . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\'">';
        echo '<div class="missing" style="display:none;">Добавьте файл: ' . e(str_replace('/media/', '', $item['image'])) . '</div>';
        echo '<div class="pad">';
        echo '<span class="tag">' . e($item['tag']) . '</span>';
        echo '<strong>' . e($item['title']) . '</strong>';
        echo '<p>' . e($item['description']) . '</p>';
        echo '</div></article>';
    }
    echo '</div></section>';
}
