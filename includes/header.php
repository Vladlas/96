<?php
require_once __DIR__ . '/functions.php';
$config = appConfig();
$siteName = getSetting('site_name', $config['site_name']);
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? $siteName) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="backdrop"></div>
<div class="wrapper">
    <header class="site-header card">
        <p class="caption">Виртуальный музей 96 кафедры</p>
        <h1><?= e($pageTitle ?? $siteName) ?></h1>
        <p class="muted"><?= e(getSetting('hero_subtitle', 'Электронный музей кафедры с современными экспозициями и архивами.')) ?></p>
    </header>
    <?php include __DIR__ . '/nav.php'; ?>
