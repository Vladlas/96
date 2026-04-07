# Запуск проекта в XAMPP (Apache)

Ниже — рабочая схема для Windows + XAMPP.

## 1) Сборка фронтенда

В корне проекта:

```bash
npm install
npm run build:xampp
```

После этого появится папка `dist/`.

## 2) Копирование в XAMPP

Скопируйте содержимое `dist/` в папку:

- `C:\xampp\htdocs\museum96\`

Итоговая структура должна содержать:

- `C:\xampp\htdocs\museum96\index.html`
- `C:\xampp\htdocs\museum96\assets\...`
- `C:\xampp\htdocs\museum96\.htaccess`
- `C:\xampp\htdocs\museum96\media\...` (ваши изображения)

## 3) Добавьте изображения

Положите медиа-файлы в:

- `C:\xampp\htdocs\museum96\media\`

Список нужных имён — в `public/media/README.md`.

## 4) Включите Apache в XAMPP

Запустите XAMPP Control Panel и включите **Apache**.

## 5) Откройте сайт

Откройте в браузере:

- `http://localhost/museum96/`

## 6) Важно для SPA-маршрутов

Файл `public/.htaccess` уже добавлен в проект и автоматически попадает в сборку. Он нужен, чтобы прямые переходы по вложенным URL не давали 404.

## 7) Частые проблемы

1. **404 на assets**
   - Убедитесь, что собираете через `npm run build:xampp`.
2. **Не подгружаются изображения**
   - Проверьте имена файлов и путь `media/...`.
3. **Apache игнорирует .htaccess**
   - В `httpd.conf` должен быть включён `AllowOverride All` для `htdocs`.
