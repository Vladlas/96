# Виртуальный музей 96 кафедры (PHP + MySQL + XAMPP)

Проект — это многостраничная мини-CMS на чистом PHP без Laravel/Symfony и без frontend-сборки.

## Что внутри

- Публичный сайт: `index.php` + `page.php` + шаблоны `pages/*`.
- Админка: `admin/*` (login/logout, dashboard, CRUD по сущностям).
- База данных MySQL: `sql/database.sql` + `sql/demo_data.sql`.
- Конфиги: `config/config.php`, `config/database.php` (PDO).
- Общие функции: `includes/functions.php`, `includes/auth.php`.

## Быстрый запуск в XAMPP

1. Скопируйте папку в `C:\xampp\htdocs\museum96\`.
2. Запустите Apache + MySQL.
3. В phpMyAdmin создайте БД `museum96` (utf8mb4).
4. Импортируйте:
   - `sql/database.sql`
   - `sql/demo_data.sql`
5. Откройте сайт: `http://localhost/museum96/`.
6. Админка: `http://localhost/museum96/admin/login.php`.

## Тестовый доступ

- login: `admin`
- password: `admin123`

## Примечание

Для подробной пошаговой инструкции см. `README.txt` и `XAMPP_SETUP.md`.
