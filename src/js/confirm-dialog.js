/**
 * Drop-in replacement for the native window.confirm() popup. Delegates
 * clicks on `<a data-confirm="message">` and submits of
 * `<form data-confirm="message">` through a Tailwind-styled modal instead
 * of the browser's own dialog. The modal markup itself lives in
 * view/footer.php and admin/view/footer.php (not built here) so its
 * classes stay inside Tailwind's content scan — the CLI never scans .js
 * files, so classes referenced only from here would be purged.
 */
(function () {
    let resolveOpen = null;

    function elements() {
        return {
            overlay: document.getElementById('confirm-dialog-overlay'),
            message: document.getElementById('confirm-dialog-message'),
            cancelBtn: document.getElementById('confirm-dialog-cancel'),
            okBtn: document.getElementById('confirm-dialog-ok'),
        };
    }

    function showConfirmDialog(message) {
        const els = elements();
        // Degrade to the native dialog rather than silently doing nothing,
        // in case a page forgets to include the modal markup.
        if (!els.overlay) {
            return Promise.resolve(window.confirm(message));
        }

        els.message.textContent = message;
        els.overlay.classList.remove('hidden');
        els.cancelBtn.focus();

        return new Promise((resolve) => {
            resolveOpen = resolve;
        });
    }

    function closeDialog(result) {
        const els = elements();
        if (els.overlay) {
            els.overlay.classList.add('hidden');
        }
        if (resolveOpen) {
            resolveOpen(result);
            resolveOpen = null;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const els = elements();
        if (!els.overlay) return;

        els.cancelBtn.addEventListener('click', () => closeDialog(false));
        els.okBtn.addEventListener('click', () => closeDialog(true));
        els.overlay.addEventListener('click', (e) => {
            if (e.target === els.overlay) closeDialog(false);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !els.overlay.classList.contains('hidden')) {
                closeDialog(false);
            }
        });
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[data-confirm]');
        if (!link) return;
        e.preventDefault();

        showConfirmDialog(link.dataset.confirm).then((ok) => {
            if (ok) window.location.href = link.href;
        });
    });

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) return;
        e.preventDefault();

        // HTMLFormElement.submit() (unlike requestSubmit()) does not
        // re-dispatch the 'submit' event, so this can't loop.
        showConfirmDialog(form.dataset.confirm).then((ok) => {
            if (ok) form.submit();
        });
    });

    window.showConfirmDialog = showConfirmDialog;
})();
