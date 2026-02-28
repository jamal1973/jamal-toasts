<?php declare(strict_types=1);

namespace Jamal\Toasts;

use Illuminate\Contracts\Session\Session;

final class ToastManager
{
    private const KEY = 'jamal_toasts';

    public function __construct(private Session $session) {}

    public function add(
        string $type,
        string $message,
        ?string $title = null,
        int $timeout = 4200,
        array $options = []
    ): void {
        $toast = array_merge([
            'id'      => bin2hex(random_bytes(6)),
            'type'    => $type,       // success|error|warning|info
            'title'   => $title,
            'message' => $message,
            'timeout' => $timeout,    // ms
            'sticky'  => false,
            'icon'    => null,        // np. "bi-check-circle"
            'meta'    => null,        // np. "Zdarzenie #123"
            'action'  => null,        // ['label'=>'Pokaż','url'=>'/..','target'=>'_self']
        ], $options);

        $list = (array) $this->session->get(self::KEY, []);
        $list[] = $toast;

        $this->session->flash(self::KEY, $list);
    }

    public function success(string $message, ?string $title = 'Sukces', int $timeout = 4200, array $options = []): void
    {
        $this->add('success', $message, $title, $timeout, $options);
    }

    public function error(string $message, ?string $title = 'Błąd', int $timeout = 6500, array $options = []): void
    {
        $this->add('error', $message, $title, $timeout, $options);
    }

    public function warning(string $message, ?string $title = 'Uwaga', int $timeout = 5200, array $options = []): void
    {
        $this->add('warning', $message, $title, $timeout, $options);
    }

    public function info(string $message, ?string $title = 'Informacja', int $timeout = 4500, array $options = []): void
    {
        $this->add('info', $message, $title, $timeout, $options);
    }

    public function pull(): array
    {
        $toasts = (array) $this->session->get(self::KEY, []);
        $this->session->forget(self::KEY);
        return $toasts;
    }
}