# Irlix Stub Helper Package

[![PHP 8.2](https://img.shields.io/badge/php-8.2-e5e7eb?style=for-the-badge&logo=php&logoColor=black&labelColor=f3f4f6)](https://www.php.net/releases/8_2_0.php)

Пакет для генерации шаблонов с помощью `.stub` файлов

### 1. Команда по созданию шаблона для бэкенда

```shell
php artisan irlix:backend-template
```

Генерируемые сущности

| Сущность               | Описание                                                |
|------------------------|---------------------------------------------------------|
| `Model`                | Модель с полями `$fillable` и трейтом `HasFactory`      |
| `Migration`            | Миграция с `id` и `timestamps`                          |
| `Controller`           | Контроллер с методами CRUD                              |
| `Service`              | Сервис с методами CRUD                                  |
| `Factory`              | Фабрика с методами `definition` и `configure`           |    
| `Store/Update Request` | Базовые реквесты с пустыми правилами `rules`            |    
| `Store/Update DTO`     | Data Transfer Object для передачи данных в сервисы      |
| `Resource`             | Базовый ресурс с пустым методом `toArray`               |
| `Policy`               | Базовая политика с пустыми правилами, возвращающие true |
| `api`                  | CRUD роуты в routes/api                                 |
| `CRUD tests`           | Feature test с PHP Attributes                           |    

### 2. Зависимости
```
- php: ^8.2
- spatie/laravel-data: ^4.5 # Для генерации DTO
``` 
