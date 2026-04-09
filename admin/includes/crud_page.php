<?php
require_once __DIR__ . '/../../includes/functions.php';

function renderCrudPage(array $cfg): void
{
    $table = $cfg['table'];
    $fields = $cfg['fields'];
    $title = $cfg['title'];
    $uploadMap = $cfg['uploads'] ?? [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? 'save';
        if ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = db()->prepare("DELETE FROM {$table} WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header('Location: ' . basename($_SERVER['PHP_SELF']));
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [];
        foreach ($fields as $name => $meta) {
            $data[$name] = $_POST[$name] ?? ($meta['default'] ?? null);
            if (($meta['type'] ?? '') === 'checkbox') {
                $data[$name] = isset($_POST[$name]) ? 1 : 0;
            }
        }

        foreach ($uploadMap as $field => $dirKey) {
            $path = handleUpload($field, appConfig()['uploads'][$dirKey], $dirKey === 'documents' ? ['pdf'] : ['jpg','jpeg','png','webp']);
            if ($path) {
                $data[$field] = $path;
            }
        }

        if ($id > 0) {
            $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
            $sql = "UPDATE {$table} SET {$set}, updated_at = NOW() WHERE id = :id";
            $data['id'] = $id;
            db()->prepare($sql)->execute($data);
        } else {
            $cols = implode(',', array_keys($data));
            $vals = implode(',', array_map(fn($k) => ":$k", array_keys($data)));
            $sql = "INSERT INTO {$table} ({$cols}, created_at, updated_at) VALUES ({$vals}, NOW(), NOW())";
            db()->prepare($sql)->execute($data);
        }

        header('Location: ' . basename($_SERVER['PHP_SELF']));
        exit;
    }

    $edit = null;
    if (!empty($_GET['edit'])) {
        $edit = fetchOne("SELECT * FROM {$table} WHERE id = :id", ['id' => (int)$_GET['edit']]);
    }
    $rows = fetchAll("SELECT * FROM {$table} ORDER BY id DESC");

    echo '<section class="panel"><h1>' . e($title) . '</h1><form method="post" enctype="multipart/form-data">';
    if ($edit) {
        echo '<input type="hidden" name="id" value="' . (int)$edit['id'] . '">';
    }

    foreach ($fields as $name => $meta) {
        $type = $meta['type'] ?? 'text';
        $label = $meta['label'] ?? $name;
        $value = $edit[$name] ?? ($meta['default'] ?? '');
        echo '<label>' . e($label) . '</label>';
        if ($type === 'textarea') {
            echo '<textarea name="' . e($name) . '">' . e((string)$value) . '</textarea>';
        } elseif ($type === 'checkbox') {
            $checked = (int)$value === 1 ? 'checked' : '';
            echo '<input type="checkbox" name="' . e($name) . '" value="1" ' . $checked . '>';
        } elseif ($type === 'file') {
            echo '<input type="file" name="' . e($name) . '">';
            if (!empty($edit[$name])) {
                echo '<p class="muted">Текущий: ' . e($edit[$name]) . '</p>';
            }
        } else {
            echo '<input type="' . e($type) . '" name="' . e($name) . '" value="' . e((string)$value) . '">';
        }
    }

    echo '<button type="submit">Сохранить</button></form></section>';
    echo '<section class="panel"><table class="table"><thead><tr><th>ID</th><th>Название</th><th>Действия</th></tr></thead><tbody>';
    foreach ($rows as $r) {
        $name = $r['title'] ?? $r['full_name'] ?? $r['setting_key'] ?? ('#' . $r['id']);
        echo '<tr><td>' . (int)$r['id'] . '</td><td>' . e((string)$name) . '</td><td><div class="row-actions">';
        echo '<a class="btn" href="' . e(basename($_SERVER['PHP_SELF'])) . '?edit=' . (int)$r['id'] . '">Ред.</a>';
        echo '<form data-delete-form method="post" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' . (int)$r['id'] . '"><button class="btn btn-danger" type="submit">Удалить</button></form>';
        echo '</div></td></tr>';
    }
    echo '</tbody></table></section>';
}
