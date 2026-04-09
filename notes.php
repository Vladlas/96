<?php
require __DIR__ . '/bootstrap.php';

$filter = $_GET['hall'] ?? 'all';
$validFilters = ['all', 'history', 'science', 'people'];
if (!in_array($filter, $validFilters, true)) {
    $filter = 'all';
}

pageHeader('Кураторские заметки', 'notes');
?>

<section class="panel">
    <h2>Фильтр по залам</h2>
    <div class="actions">
        <a class="btn <?= $filter === 'all' ? '' : 'ghost' ?>" href="notes.php?hall=all">Все</a>
        <a class="btn <?= $filter === 'history' ? '' : 'ghost' ?>" href="notes.php?hall=history">История</a>
        <a class="btn <?= $filter === 'science' ? '' : 'ghost' ?>" href="notes.php?hall=science">Наука</a>
        <a class="btn <?= $filter === 'people' ? '' : 'ghost' ?>" href="notes.php?hall=people">Люди</a>
    </div>
</section>

<section class="panel">
    <div class="cards two">
        <?php foreach ($notes as $note): ?>
            <?php if ($filter !== 'all' && $note['hallId'] !== $filter) {
                continue;
            } ?>
            <article class="card text-only">
                <div class="card-pad">
                    <span class="tag"><?= e($hallMap[$note['hallId']]['title'] ?? $note['hallId']) ?></span>
                    <h3><?= e($note['title']) ?></h3>
                    <p><?= e($note['short']) ?></p>
                    <p class="muted"><?= e($note['content']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php pageFooter(); ?>
