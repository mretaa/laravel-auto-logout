# Laravel-auto-logout
**Laravel Auto Logout** is a Laravel package that automatically manages the logout of inactive users. It includes a **Tailwind CSS modal** that appears before logout to allow the user to end or extend their session.

## ✨ Features

- Automatic logout after a period of inactivity (configurable).
- Display of a *Tailwind modal* asking if the user wants to stay logged in.
- Configurable countdown before logout.
- Simple integration with a Blade @autoLogout directive.
- Middleware to enhance server-side security.

## 📦 Installation

Install via Composer:

```bash
composer require mretaa/laravel-auto-logout
```
## ⚙️ Configuration

Publish configuration files, views, and public assets:
```bash
php artisan vendor:publish --provider=“Mretaa\AutoLogout\IdleGuardServiceProvider”
```

This will create:

- config/idle-guard.php (package configuration)

- resources/views/vendor/auto-logout/ (modal view)

- public/vendor/auto-logout/js/ (JS script)


**Configuration example (config/idle-guard.php)**
```php
    return [
        ‘idle_time’   => 300,     // Time (seconds) before displaying the modal
        ‘logout_time’ => 60,      // Time (seconds) after modal before logging out
        ‘redirect_url’ => ‘/logout’, // Redirect URL after logging out
    ]; ```

