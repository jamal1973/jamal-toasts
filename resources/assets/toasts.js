(() => {
  const stack = document.getElementById('jamal-toast-stack');
  if (!stack) return;

  const max = parseInt(stack.dataset.max || '4', 10);

  let icons = {};
  try { icons = JSON.parse(stack.dataset.icons || '{}') || {}; } catch(_) {}

  let initial = [];
  try { initial = JSON.parse(stack.dataset.toasts || '[]') || []; } catch(_) {}

  const DEFAULT_ICONS = {
    success: 'bi-check-circle',
    info: 'bi-info-circle',
    warning: 'bi-exclamation-triangle',
    error: 'bi-x-circle',
  };

  function escapeHtml(s) {
    return String(s)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function clampStack() {
    const items = Array.from(stack.querySelectorAll('.jamal-toast'));
    if (items.length <= max) return;
    const overflow = items.length - max;
    for (let i = 0; i < overflow; i++) closeToast(items[i], true);
  }

  function closeToast(el, immediate = false) {
    if (!el) return;
    const done = () => el.remove();
    if (immediate) return done();
    el.classList.remove('jt-in');
    el.classList.add('jt-out');
    el.addEventListener('animationend', done, { once: true });
  }

  function renderToast(t) {
    const type = (t.type || 'info').toLowerCase();
    const title = t.title || '';
    const msg = t.message || '';
    const meta = t.meta || '';
    const sticky = !!t.sticky;
    const timeout = Number.isFinite(+t.timeout) ? +t.timeout : 4200;

    const icon = t.icon || icons[type] || DEFAULT_ICONS[type] || DEFAULT_ICONS.info;
    const action = t.action && t.action.url ? t.action : null;

    const el = document.createElement('div');
    el.className = 'jamal-toast jt-in';
    el.dataset.type = type;

    el.innerHTML = `
      <div class="jamal-toast-inner">
        <div class="jamal-ico"><i class="bi ${escapeHtml(icon)}"></i></div>
        <div>
          ${title ? `<div class="jamal-title">${escapeHtml(title)}</div>` : ``}
          <p class="jamal-msg">${escapeHtml(msg)}</p>

          <div class="jamal-meta">
            ${meta ? `<span>${escapeHtml(meta)}</span>` : ``}
            ${action ? `<a class="jamal-action" href="${escapeHtml(action.url)}" target="${escapeHtml(action.target || '_self')}">
              <i class="bi bi-box-arrow-up-right"></i> ${escapeHtml(action.label || 'Otwórz')}
            </a>` : ``}
          </div>
        </div>
        <button type="button" class="jamal-close" aria-label="Zamknij">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="jamal-progress"><span></span></div>
    `;

    el.querySelector('.jamal-close').addEventListener('click', () => closeToast(el));

    const bar = el.querySelector('.jamal-progress > span');

    let timerId = null;
    let anim = null;
    let remaining = timeout;
    let startAt = 0;

    const start = () => {
      if (sticky) { bar.style.transform = 'scaleX(0)'; return; }
      startAt = Date.now();

      anim = bar.animate(
        [{ transform: `scaleX(${remaining / timeout})` }, { transform: 'scaleX(0)' }],
        { duration: remaining, easing: 'linear', fill: 'forwards' }
      );

      timerId = window.setTimeout(() => closeToast(el), remaining);
    };

    const pause = () => {
      if (sticky) return;
      if (timerId) { clearTimeout(timerId); timerId = null; }
      if (anim) { anim.cancel(); anim = null; }
      const passed = Date.now() - startAt;
      remaining = Math.max(50, remaining - passed);
    };

    el.addEventListener('mouseenter', pause);
    el.addEventListener('mouseleave', start);

    stack.prepend(el);
    clampStack();
    start();
  }

  initial.forEach(renderToast);

  // optionalne API w JS
  window.JamalToast = {
    push: (toast) => renderToast(toast),
    success: (m, title='Sukces', timeout=4200) => renderToast({type:'success', message:m, title, timeout}),
    error:   (m, title='Błąd', timeout=6500) => renderToast({type:'error', message:m, title, timeout}),
    warning: (m, title='Uwaga', timeout=5200) => renderToast({type:'warning', message:m, title, timeout}),
    info:    (m, title='Informacja', timeout=4500) => renderToast({type:'info', message:m, title, timeout}),
  };
})();