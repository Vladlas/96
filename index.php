<?php
require __DIR__ . '/bootstrap.php';

$mainSection = $museumContent[0];

pageHeader('Кафедральная экспозиция и летопись', 'home');
?>

<section class="split">
    <article class="panel">
        <p class="chipline">Центральная витрина</p>
        <h2><?= e($mainSection['heading']) ?></h2>
        <p class="muted"><?= e($mainSection['description']) ?></p>
        <div class="pill-grid">
            <?php foreach ($mainSection['items'] as $item): ?>
                <span class="pill"><?= e($item) ?></span>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <a class="btn" href="hall.php?hall=history">Начать экскурсию</a>
            <a class="btn ghost" href="gallery.php">Открыть фотолетопись</a>
        </div>
    </article>

    <aside class="panel">
        <h2>Календарь кафедры</h2>
        <?php foreach (array_slice($calendarEvents, 0, 3) as $event): ?>
            <div class="event">
                <small><?= e($event['date']) ?></small>
                <p><?= e($event['title']) ?></p>
            </div>
        <?php endforeach; ?>
    </aside>
</section>

<?php renderGalleryBlock('Кафедральная символика', 'Эмблемы кафедры и связанные знаки из предоставленного набора материалов', $departmentHeraldry, true); ?>

<?php pageFooter(); ?>
