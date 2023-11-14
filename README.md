## Learning FilamentPHP

## Quick Start to run locally
- Clone repository
- Run composer install
- copy .env.example file to .env and .env.testing
- Open .env/.env.testing and setup database connection
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate`
- Finally, run `php artisan serve`

## Running Tests

```
php artisan test
```

**Note:** Make sure you set up the test variables in the `.env.testing` file
