## Library API

### Features
- Authenticated book creation: `POST /api/books` secured with Sanctum tokens.
- Search authors by book title or name: `GET /api/authors?search={query}`.
- Interactive console helper: `php artisan author:create` asks for first/last name and creates the author.

### Requirements
- PHP 8.2+
- Composer

### Installation
composer install
php artisan migrate
php artisan test

### API Endpoints
- GET /api/books
- GET /api/books/{id}
- POST /api/books (Sanctum)
- PUT /api/books/{id}
- DELETE /api/books/{id}
- GET /api/authors
- GET /api/authors/{id}
- GET /api/authors?search=

### Notes
- Token-based auth via Laravel Sanctum
- Clean architecture (Actions, Repositories, DTOs)
