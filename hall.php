<?php
require __DIR__ . '/bootstrap.php';

$hallId = $_GET['hall'] ?? 'history';
if (!isset($hallMap[$hallId]) || $hallId === 'main') {
    $hallId = 'history';
}

$section = null;
foreach ($museumContent as $entry) {
    if ($entry['hallId'] === $hallId) {
        $section = $entry;
        break;
    }
}

$hall = $hallMap[$hallId];
pageHeader($hall['title'], $hallId);
?>

<section class="panel">
    <p class="chipline"><?= e($hall['subtitle']) ?></p>
    <h2><?= e($section['heading']) ?></h2>
    <p class="muted"><?= e($section['description']) ?></p>
    <div class="pill-grid">
        <?php foreach ($section['items'] as $item): ?>
            <span class="pill"><?= e($item) ?></span>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <h2>Кураторские заметки по залу</h2>
    <div class="cards two">
        <?php foreach ($notes as $note): ?>
            <?php if ($note['hallId'] !== $hallId) {
                continue;
            } ?>
            <article class="card text-only">
                <div class="card-pad">
                    <span class="tag"><?= e($hall['title']) ?></span>
                    <h3><?= e($note['title']) ?></h3>
                    <p><?= e($note['short']) ?></p>
                    <p class="muted"><?= e($note['content']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php pageFooter(); ?>
