<?php
$activePage = 'home';
$pageTitle = 'Главный холл';
require __DIR__ . '/includes/header.php';
$halls = getHalls();
$events = getActiveEvents(4);
$gallery = getGallery(4);
?>
<section class="grid two">
    <article class="card hero-block">
        <p class="caption">Витрина экспозиции</p>
        <h2><?= e(getSetting('showcase_title', 'Центральная экспозиция кафедры')) ?></h2>
        <p class="muted"><?= e(getSetting('showcase_description', 'История, достижения и современные разработки кафедры в едином цифровом пространстве.')) ?></p>
        <div class="chips">
            <?php foreach ($halls as $hall): ?>
                <a class="chip" href="page.php?p=<?= e($hall['slug']) ?>"><?= e($hall['title']) ?></a>
            <?php endforeach; ?>
        </div>
    </article>
    <aside class="card">
        <h3>Календарь кафедры</h3>
        <?php foreach ($events as $event): ?>
            <div class="event">
                <strong><?= e($event['title']) ?></strong>
                <p class="muted small"><?= e($event['date_start']) ?> · <?= e($event['event_type']) ?></p>
            </div>
        <?php endforeach; ?>
    </aside>
</section>
<section class="card">
    <h3>Экспонаты дня</h3>
    <div class="gallery-grid">
        <?php foreach ($gallery as $item): ?>
            <article class="tile">
                <img src="<?= e($item['image_path']) ?>" alt="<?= e($item['title']) ?>">
                <div><strong><?= e($item['title']) ?></strong><p class="muted small"><?= e($item['album']) ?></p></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
