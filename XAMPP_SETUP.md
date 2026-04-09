# XAMPP_SETUP (museum96)

Краткая инструкция для локального запуска проекта в XAMPP.

## 1) Куда копировать

Скопируйте проект в:

`C:\xampp\htdocs\museum96\`

## 2) Запуск сервисов

В XAMPP Control Panel запустите:

- Apache
- MySQL

## 3) Создание БД в phpMyAdmin

1. Откройте `http://localhost/phpmyadmin/`
2. Создайте базу `museum96` (кодировка `utf8mb4`)
3. Импортируйте SQL-файлы в таком порядке:
   - `sql/database.sql`
   - `sql/demo_data.sql`

## 4) Проверка URL

- Публичный сайт: `http://localhost/museum96/`
- Админка: `http://localhost/museum96/admin/login.php`

## 5) Тестовый доступ в админку

- login: `admin`
- password: `admin123`

## 6) Файлы загрузок

Папки должны существовать и быть доступны для записи Apache/PHP:

- `uploads/gallery/`
- `uploads/documents/`
- `uploads/covers/`
