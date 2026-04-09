<?php
session_start();

require __DIR__ . '/data.php';

$hallMap = [];
foreach ($halls as $hall) {
    $hallMap[$hall['id']] = $hall;
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

        if (isset($hallMap[$hallId]) && $hallId !== 'main' && $title !== '' && $short !== '' && $content !== '') {
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

    $redirect = $_POST['redirect_to'] ?? 'index.php';
    header('Location: ' . $redirect);
    exit;
}

$notes = $_SESSION['notes'];
$calendarEvents = $_SESSION['calendar'];

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function pageHeader(string $title, string $activeTab): void
{
    $tabs = [
        'home' => ['label' => 'Главная', 'href' => 'index.php'],
        'history' => ['label' => 'Зал истории', 'href' => 'hall.php?hall=history'],
        'science' => ['label' => 'Зал науки', 'href' => 'hall.php?hall=science'],
        'people' => ['label' => 'Зал людей', 'href' => 'hall.php?hall=people'],
        'gallery' => ['label' => 'Фотолетопись', 'href' => 'gallery.php'],
        'routes' => ['label' => 'Маршруты', 'href' => 'routes.php'],
        'notes' => ['label' => 'Заметки', 'href' => 'notes.php'],
        'admin' => ['label' => 'Админ', 'href' => 'admin.php'],
    ];

    echo '<!doctype html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' — Виртуальный музей кафедры 96</title>';
    echo '<link rel="stylesheet" href="styles.css"></head><body><div class="bg-glow"></div><main class="layout">';
    echo '<header class="panel hero"><p class="chipline">Виртуальный музей кафедры 96</p><h1>' . e($title) . '</h1>';
    echo '<p class="muted">Кафедральная экспозиция, летопись, маршруты и кураторские заметки в едином цифровом пространстве.</p></header>';
    echo '<nav class="tabs">';
    foreach ($tabs as $key => $tab) {
        $class = $key === $activeTab ? 'tab active' : 'tab';
        echo '<a class="' . $class . '" href="' . e($tab['href']) . '">' . e($tab['label']) . '</a>';
    }
    echo '</nav>';
}

function pageFooter(): void
{
    echo '</main></body></html>';
}

function renderGalleryBlock(string $title, string $subtitle, array $items, bool $compact = false): void
{
    $gridClass = $compact ? 'cards compact' : 'cards';
    echo '<section class="panel"><h2>' . e($title) . '</h2><p class="muted">' . e($subtitle) . '</p><div class="' . $gridClass . '">';
    foreach ($items as $item) {
        echo '<article class="card">';
        echo '<img src="' . e($item['image']) . '" alt="' . e($item['title']) . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'grid\'">';
        echo '<div class="img-fallback">Добавьте файл:<br>' . e(str_replace('/media/', '', $item['image'])) . '</div>';
        echo '<div class="card-pad"><span class="tag">' . e($item['tag']) . '</span><h3>' . e($item['title']) . '</h3><p>' . e($item['description']) . '</p></div>';
        echo '</article>';
    }
    echo '</div></section>';
}
