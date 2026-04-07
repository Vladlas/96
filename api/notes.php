<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$db = getDb();

if ($method === 'GET') {
    $rows = $db->query('SELECT id, hall_id, title, short, content FROM notes ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    $result = array_map(static fn(array $row): array => [
        'id' => (string) $row['id'],
        'hallId' => $row['hall_id'],
        'title' => $row['title'],
        'short' => $row['short'],
        'content' => $row['content']
    ], $rows);

    sendJson($result);
}

if ($method === 'POST') {
    $body = readBody();
    $hallId = trim((string) ($body['hallId'] ?? ''));
    $title = trim((string) ($body['title'] ?? ''));
    $short = trim((string) ($body['short'] ?? ''));
    $content = trim((string) ($body['content'] ?? ''));

    if ($hallId === '' || $title === '' || $short === '' || $content === '') {
        sendJson(['error' => 'invalid_payload'], 422);
    }

    $stmt = $db->prepare('INSERT INTO notes (hall_id, title, short, content) VALUES (:hall_id, :title, :short, :content)');
    $stmt->execute([
        ':hall_id' => $hallId,
        ':title' => $title,
        ':short' => $short,
        ':content' => $content
    ]);

    sendJson([
        'id' => (string) $db->lastInsertId(),
        'hallId' => $hallId,
        'title' => $title,
        'short' => $short,
        'content' => $content
    ], 201);
}

if ($method === 'DELETE') {
    $id = trim((string) ($_GET['id'] ?? ''));
    if ($id === '') {
        sendJson(['error' => 'missing_id'], 422);
    }

    $stmt = $db->prepare('DELETE FROM notes WHERE id = :id');
    $stmt->execute([':id' => $id]);

    sendJson(['ok' => true]);
}

sendJson(['error' => 'method_not_allowed'], 405);
