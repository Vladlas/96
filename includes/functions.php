<?php
require_once __DIR__ . '/../config/database.php';

function appConfig(): array
{
    static $config;
    if (!$config) {
        $config = require __DIR__ . '/../config/config.php';
    }
    return $config;
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function fetchAll(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetchOne(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

function fetchValue(string $sql, array $params = []): mixed
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function getSetting(string $key, string $default = ''): string
{
    $row = fetchOne('SELECT setting_value FROM settings WHERE setting_key = :k', ['k' => $key]);
    return $row['setting_value'] ?? $default;
}

function getPageContent(string $pageKey): array
{
    $rows = fetchAll('SELECT * FROM pages_content WHERE page_key = :k ORDER BY section_key', ['k' => $pageKey]);
    $result = [];
    foreach ($rows as $row) {
        $result[$row['section_key']] = $row;
    }
    return $result;
}

function getActiveEvents(int $limit = 6): array
{
    return fetchAll('SELECT * FROM events WHERE is_active = 1 ORDER BY date_start ASC LIMIT ' . (int)$limit);
}

function getHalls(): array
{
    return fetchAll('SELECT * FROM halls WHERE is_active = 1 ORDER BY sort_order, id');
}

function getGallery(int $limit = 12): array
{
    return fetchAll('SELECT * FROM gallery WHERE is_active = 1 ORDER BY sort_order, id LIMIT ' . (int)$limit);
}

function getStaff(int $limit = 24): array
{
    return fetchAll('SELECT * FROM staff WHERE is_active = 1 ORDER BY sort_order, id LIMIT ' . (int)$limit);
}

function getRecords(string $table, int $limit = 100): array
{
    $allowed = ['halls','staff','publications','achievements','certificates','documents','gallery','events','settings','pages_content'];
    if (!in_array($table, $allowed, true)) {
        return [];
    }
    return fetchAll("SELECT * FROM {$table} ORDER BY id DESC LIMIT " . (int)$limit);
}

function handleUpload(string $field, string $targetDir, array $allowed = ['jpg','jpeg','png','webp','pdf']): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $original = $_FILES[$field]['name'];
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $name = uniqid($field . '_', true) . '.' . $ext;
    $full = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $name;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $full)) {
        return null;
    }

    return str_replace('\\', '/', str_replace(realpath(__DIR__ . '/..'), '', realpath($full)));
}
