# Запуск проекта в XAMPP (Apache + PHP + SQLite)

Ниже — рабочая схема для Windows + XAMPP.

## 1) Сборка фронтенда

В корне проекта:

```bash
npm install
npm run build:xampp
```

После этого появится папка `dist/`.

## 2) Копирование в XAMPP

Создайте папку проекта:

- `C:\xampp\htdocs\museum96\`

Скопируйте туда:

1. **всё содержимое** `dist/` (React-фронтенд),
2. папку `api/` (PHP API),
3. папку `data/` (SQLite-файл будет создан автоматически),
4. папку `media/` с изображениями,
5. файл `.htaccess` (из `public/.htaccess`).

Итоговая структура:

- `C:\xampp\htdocs\museum96\index.html`
- `C:\xampp\htdocs\museum96\assets\...`
- `C:\xampp\htdocs\museum96\api\notes.php`
- `C:\xampp\htdocs\museum96\api\events.php`
- `C:\xampp\htdocs\museum96\api\db.php`
- `C:\xampp\htdocs\museum96\data\`
- `C:\xampp\htdocs\museum96\media\...`
- `C:\xampp\htdocs\museum96\.htaccess`

## 3) Добавьте изображения

Положите медиа-файлы в:

- `C:\xampp\htdocs\museum96\media\`

Список нужных имён — в `public/media/README.md`.

## 4) Включите Apache и проверьте PHP SQLite

В XAMPP Control Panel:

- запустите **Apache**.

В `php.ini` должны быть активны расширения:

- `extension=pdo_sqlite`
- `extension=sqlite3`

## 5) Откройте сайт

- `http://localhost/museum96/`

## 6) Как работает БД

- База создаётся автоматически в `data/museum.sqlite` при первом запросе к API.
- Админка работает через:
  - `GET/POST/DELETE /api/notes.php`
  - `GET/POST/DELETE /api/events.php`

## 7) Важно для SPA-маршрутов

`.htaccess` уже включает fallback на `index.html` для фронтенда, но не ломает `api/*.php`.

## 8) Частые проблемы

1. **404 на assets**
   - Убедитесь, что собираете через `npm run build:xampp`.
2. **500 на API**
   - Проверьте, что включены `pdo_sqlite` и `sqlite3`.
3. **Нет записи в БД**
   - Проверьте права на `C:\xampp\htdocs\museum96\data\`.


## 9) Если в PR конфликт с `main`

Выполните локально:

```bash
git fetch origin
git merge origin/main
# или: git rebase origin/main
```

Затем вручную исправьте конфликтные файлы и завершите:

```bash
git add .
git commit
```
