<?php
require_once __DIR__ . '/../includes/auth.php';
if (isAdminLoggedIn()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (loginAdmin(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}
?><!doctype html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Вход</title><link rel="stylesheet" href="../assets/css/admin.css"></head><body><main class="content"><section class="panel" style="max-width:420px;margin:60px auto"><h1>Вход в админку</h1><?php if($error): ?><p style="color:#f87171"><?= e($error) ?></p><?php endif; ?><form method="post"><input name="username" placeholder="Логин" required><input type="password" name="password" placeholder="Пароль" required><button type="submit">Войти</button></form><p class="muted">Демо: admin / admin123</p></section></main></body></html>