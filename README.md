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
