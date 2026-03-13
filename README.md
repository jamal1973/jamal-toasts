# jamal/toasts

Polished toast notifications for Laravel (Blade).  
Includes: animations, progress bar, hover pause, action button, dark-mode.

## Install

### 1) Require via Composer (VCS)
Add repository to your project's `composer.json`:

```json
"repositories": [
  { "type": "vcs", "url": "git@github.com:jamal1973/jamal-toasts.git" }
],
"require": {
  "jamal/toasts": "dev-main"
}
```

Then publish package files:

```bash
php artisan vendor:publish --tag=jamal-toasts-assets
php artisan vendor:publish --tag=jamal-toasts-config
```
