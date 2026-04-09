<?php
require __DIR__ . '/bootstrap.php';

pageHeader('Быстрые маршруты', 'routes');
?>

<section class="panel">
    <h2>Маршруты кураторов</h2>
    <div class="cards two">
        <?php foreach ($curatorRoutes as $route): ?>
            <article class="card text-only">
                <div class="card-pad">
                    <span class="tag">Маршрут</span>
                    <h3><?= e($route['title']) ?></h3>
                    <p class="muted">Длительность: <?= e($route['duration']) ?></p>
                    <p>Последовательность залов:
                        <?php
                        $labels = [];
                        foreach ($route['hallIds'] as $id) {
                            $labels[] = $hallMap[$id]['title'] ?? $id;
                        }
                        ?>
                        <strong><?= e(implode(' → ', $labels)) ?></strong>
                    </p>
                    <a class="btn" href="hall.php?hall=<?= e($route['hallIds'][0]) ?>">Перейти к первому залу</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <h2>Ближайшие события</h2>
    <?php foreach (array_slice($calendarEvents, 0, 6) as $event): ?>
        <div class="event">
            <small><?= e($event['date']) ?></small>
            <p><?= e($event['title']) ?></p>
        </div>
    <?php endforeach; ?>
</section>

<?php pageFooter(); ?>
