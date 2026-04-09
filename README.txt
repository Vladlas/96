Виртуальный музей 96 кафедры — запуск в XAMPP

1) Копирование
- Скопируйте папку проекта в: C:\xampp\htdocs\museum96\

2) Запуск сервисов
- Откройте XAMPP Control Panel
- Запустите Apache и MySQL

3) Создание БД и импорт
- Откройте phpMyAdmin: http://localhost/phpmyadmin/
- Создайте БД: museum96 (utf8mb4)
- Импортируйте файлы по очереди:
  1. sql/database.sql
  2. sql/demo_data.sql

4) Открытие сайта
- Публичная часть: http://localhost/museum96/
- Любая страница: http://localhost/museum96/page.php?p=history

5) Вход в админку
- URL: http://localhost/museum96/admin/login.php
- Логин: admin
- Пароль: admin123

6) Загрузка файлов
- Папки для загрузок:
  uploads/gallery/
  uploads/documents/
  uploads/covers/
- Права записи должны быть доступны PHP (Apache).

7) Стек и безопасность
- PHP 8+, MySQL, PDO
- prepared statements
- password_hash/password_verify
- сессии для защиты админки
