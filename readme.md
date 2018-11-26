### Installation

Require packages:
```bash
composer require stylemix/laravel-settings
```

Publish package:
```bash
php artisan vendor:publish --provider="Stylemix\Settings\ServiceProvider"
```

If you use database storage for settings:
```bash
php artisan migrate
```


### Settings configuration

File `config/settings.php`:

- `store`: set your type of storage
