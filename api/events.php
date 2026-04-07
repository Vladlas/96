<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$db = getDb();

if ($method === 'GET') {
    $rows = $db->query('SELECT id, date, title FROM events ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    $result = array_map(static fn(array $row): array => [
        'id' => (string) $row['id'],
        'date' => $row['date'],
        'title' => $row['title']
    ], $rows);

    sendJson($result);
}

if ($method === 'POST') {
    $body = readBody();
    $date = trim((string) ($body['date'] ?? ''));
    $title = trim((string) ($body['title'] ?? ''));

    if ($date === '' || $title === '') {
        sendJson(['error' => 'invalid_payload'], 422);
    }

    $stmt = $db->prepare('INSERT INTO events (date, title) VALUES (:date, :title)');
    $stmt->execute([
        ':date' => $date,
        ':title' => $title
    ]);

    sendJson([
        'id' => (string) $db->lastInsertId(),
        'date' => $date,
        'title' => $title
    ], 201);
}

if ($method === 'DELETE') {
    $id = trim((string) ($_GET['id'] ?? ''));
    if ($id === '') {
        sendJson(['error' => 'missing_id'], 422);
    }

    $stmt = $db->prepare('DELETE FROM events WHERE id = :id');
    $stmt->execute([':id' => $id]);

    sendJson(['ok' => true]);
}

sendJson(['error' => 'method_not_allowed'], 405);
