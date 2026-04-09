# Запуск проекта в XAMPP (Apache + PHP)

Проект работает как чистый PHP-сайт (многостраничный, без Node.js/npm/Vite/SQLite API).

## 1) Копирование в XAMPP

Скопируйте проект в:

- `C:\xampp\htdocs\museum96\`

Проверьте, что на месте:

- страницы: `index.php`, `hall.php`, `gallery.php`, `routes.php`, `notes.php`, `admin.php`
- ядро: `bootstrap.php`, `data.php`, `styles.css`
- медиа: `media\...`

## 2) Добавьте изображения

Положите медиа-файлы в:

- `C:\xampp\htdocs\museum96\media\`

Список имён — `public/media/README.md`.

## 3) Запустите Apache

В XAMPP Control Panel включите **Apache**.

## 4) Откройте сайт

- Главная: `http://localhost/museum96/`
- Галерея: `http://localhost/museum96/gallery.php`
- Админ: `http://localhost/museum96/admin.php`

## 5) Важно

Добавленные в админке заметки/события хранятся в `$_SESSION`:

- сохраняются только в рамках текущей сессии браузера;
- исчезают после очистки cookies/сессии.

## 6) Частые проблемы

1. **Открывается список файлов вместо сайта**
   - Проверьте, что входная точка `index.php` существует в `museum96`.
2. **Apache не запускается**
   - Проверьте занятость порта `80`.
3. **Нет изображений**
   - Проверьте путь `media/...` и имена файлов.
