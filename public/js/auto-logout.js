// auto-logout.js

(function () {
    // Récupère la config depuis Blade
    const idleDelay = (window.autoLogoutConfig?.idle_time || 300) * 1000;
    const logoutDelay = (window.autoLogoutConfig?.logout_time || 60);
    const redirectUrl = window.autoLogoutConfig?.redirect_url || '/logout';

    let idleTimer, countdownTimer, countdown;
    const modal = document.getElementById('inactivity-modal');
    const continueBtn = document.getElementById('continue-session');
    const logoutBtn = document.getElementById('logout-session');
    const countdownSpan = document.getElementById('logout-countdown');

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function logoutUser() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = redirectUrl;
        // CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = getCsrfToken();
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    function showModal() {
        if (modal) {
            modal.classList.remove('hidden');
            countdown = logoutDelay;
            updateCountdown();
            countdownTimer = setInterval(() => {
                countdown--;
                updateCountdown();
                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    hideModal();
                    logoutUser();
                }
            }, 1000);
        }
    }

    function hideModal() {
        if (modal) {
            modal.classList.add('hidden');
            clearInterval(countdownTimer);
        }
    }

    function updateCountdown() {
        if (countdownSpan) {
            countdownSpan.textContent = countdown;
        }
    }

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        if (modal && !modal.classList.contains('hidden')) return; // Ne rien faire si le modal est affiché
        idleTimer = setTimeout(() => {
            showModal();
        }, idleDelay);
    }

    ['mousemove', 'keydown', 'scroll', 'touchstart'].forEach(evt => {
        window.addEventListener(evt, resetIdleTimer);
    });
    resetIdleTimer();

    if (continueBtn) {
        continueBtn.addEventListener('click', function () {
            hideModal();
            resetIdleTimer();
        });
    }
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            hideModal();
            logoutUser();
        });
    }
})();
