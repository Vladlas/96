<?php
$menu = [
    'home' => ['title' => 'Главный холл', 'url' => 'index.php'],
    'about' => ['title' => 'О кафедре', 'url' => 'page.php?p=about'],
    'history' => ['title' => 'История', 'url' => 'page.php?p=history'],
    'structure' => ['title' => 'Структура', 'url' => 'page.php?p=structure'],
    'staff' => ['title' => 'Личный состав', 'url' => 'page.php?p=staff'],
    'education' => ['title' => 'Учебный процесс', 'url' => 'page.php?p=education'],
    'achievements' => ['title' => 'Достижения', 'url' => 'page.php?p=achievements'],
    'science' => ['title' => 'Наука', 'url' => 'page.php?p=science'],
    'certificates' => ['title' => 'Свидетельства ПО', 'url' => 'page.php?p=certificates'],
    'documents' => ['title' => 'Документы', 'url' => 'page.php?p=documents'],
    'gallery' => ['title' => 'Галерея', 'url' => 'page.php?p=gallery'],
];
?>
<nav class="main-nav">
    <?php foreach ($menu as $key => $item): ?>
        <a class="nav-link <?= ($activePage ?? '') === $key ? 'active' : '' ?>" href="<?= e($item['url']) ?>"><?= e($item['title']) ?></a>
    <?php endforeach; ?>
    <a class="nav-link admin" href="admin/">Админка</a>
</nav>
