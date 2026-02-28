@once
  <link rel="stylesheet" href="{{ $assets['css'] ?? '/vendor/jamal-toasts/toasts.css' }}">
  <script defer src="{{ $assets['js'] ?? '/vendor/jamal-toasts/toasts.js' }}"></script>
@endonce

<div
  id="jamal-toast-stack"
  class="jamal-toast-stack jamal-pos-{{ $position }}"
  data-max="{{ $max }}"
  data-icons='@json($icons, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)'
  data-toasts='@json($toasts, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)'>
</div>