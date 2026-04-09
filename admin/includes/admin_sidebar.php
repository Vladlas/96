<?php $p = basename($_SERVER['PHP_SELF']); $menu=[
'dashboard.php'=>'Dashboard','halls.php'=>'Залы','staff.php'=>'Состав','publications.php'=>'Публикации','achievements.php'=>'Достижения','certificates.php'=>'Свидетельства','documents.php'=>'Документы','gallery.php'=>'Галерея','events.php'=>'События','settings.php'=>'Настройки']; ?>
<aside class="sidebar"><h2>Admin</h2><?php foreach($menu as $file=>$label): ?><a class="<?= $p===$file?'active':'' ?>" href="<?= e($file) ?>"><?= e($label) ?></a><?php endforeach; ?><a href="logout.php">Выход</a><a href="../index.php">На сайт</a></aside>
