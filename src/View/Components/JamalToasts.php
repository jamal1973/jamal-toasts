<?php declare(strict_types=1);

namespace Jamal\Toasts\View\Components;

use Illuminate\View\Component;

final class JamalToasts extends Component
{
    public function render()
    {
        return view('jamal-toasts::components.toasts', [
            'toasts'   => jamal_toast()->pull(),
            'position' => config('jamal_toasts.position', 'top-right'),
            'max'      => (int) config('jamal_toasts.max', 4),
            'icons'    => (array) config('jamal_toasts.icons', []),
            'assets'   => (array) config('jamal_toasts.assets', []),
        ]);
    }
}