<?php include __DIR__ . '/includes/admin_header.php'; ?>
<section class="panel"><h1>Dashboard</h1><div class="stats">
<?php
$stats=[
'Залы'=>fetchValue('SELECT COUNT(*) FROM halls'),
'Документы'=>fetchValue('SELECT COUNT(*) FROM documents'),
'Публикации'=>fetchValue('SELECT COUNT(*) FROM publications'),
'Достижения'=>fetchValue('SELECT COUNT(*) FROM achievements'),
'Изображения'=>fetchValue('SELECT COUNT(*) FROM gallery'),
'События'=>fetchValue('SELECT COUNT(*) FROM events'),
];
foreach($stats as $k=>$v){echo '<div class="stat"><small>'.$k.'</small><h2>'.(int)$v.'</h2></div>';}
?>
</div></section>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>