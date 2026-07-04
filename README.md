# ShopFlow - Headless E-commerce Order API

Бэкенд для обработки заказов интернет-магазина: каталог, корзина,
заказы с полным жизненным циклом, асинхронная обработка через очереди.

Учебный pet-project для отработки продакшн-практик: чистая архитектура,
событийная обработка, кэширование, тестирование, CI/CD и мониторинг.

> ⚠️ Проект в активной разработке. Дорожная карта - ниже.

## Технологический стек

| Слой               | Технология           |
| ------------------ | -------------------- |
| Язык / фреймворк   | PHP 8.4, Laravel 13  |
| База данных        | PostgreSQL 16        |
| Кэш / сессии       | Redis 7              |
| Очереди            | RabbitMQ             |
| Веб-сервер         | Nginx + PHP-FPM      |
| Аутентификация     | JWT                  |
| Документация API   | OpenAPI / Swagger    |
| Тесты              | Pest / PHPUnit       |
| Статический анализ | Larastan (PHPStan)   |
| CI/CD              | GitHub Actions       |
| Мониторинг         | Prometheus + Grafana |
| Оркестрация        | Docker Compose       |

## Быстрый старт

Требуется только Docker и Docker Compose.

```bash
git clone https://github.com/<username>/shopflow.git
cd shopflow

cp .env.example .env

echo "UID=$(id -u)" >> .env
echo "GID=$(id -g)" >> .env

docker compose build
docker compose up -d

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Проверка:

- Приложение: http://localhost:8080
- Health-check: http://localhost:8080/health

## Архитектура

_Диаграмма и описание доменной модели появятся по мере развития проекта._

## Дорожная карта

- [x] Docker-окружение (Nginx, PHP-FPM, PostgreSQL, Redis)
- [x] Health-check endpoint
- [ ] Доменная модель: каталог, пользователи
- [ ] JWT-аутентификация
- [ ] REST API + OpenAPI/Swagger
- [ ] Заказы: state machine, транзакции, идемпотентность
- [ ] RabbitMQ: доменные события, воркеры
- [ ] Redis-кэширование и rate limiting
- [ ] Тесты (Pest) и статический анализ
- [ ] CI/CD (GitHub Actions)
- [ ] Логирование и мониторинг (Prometheus + Grafana)
- [ ] Деплой на VPS

## Лицензия

MIT
