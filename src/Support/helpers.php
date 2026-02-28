<?php declare(strict_types=1);

use Jamal\Toasts\ToastManager;

if (! function_exists('jamal_toast')) {
    function jamal_toast(): ToastManager
    {
        return app(ToastManager::class);
    }
}