import './bootstrap';
import Alpine from 'alpinejs';

const THEME_KEY = 'bc-theme';
const root = document.documentElement;

function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    window.dispatchEvent(new CustomEvent('bc-theme-changed', { detail: theme }));
}

function initTheme() {
    try {
        const stored = window.localStorage.getItem(THEME_KEY);
        const initial = stored === 'dark' || stored === 'light' ? stored : 'light';
        applyTheme(initial);
    } catch (e) {
        applyTheme('light');
    }
}

window.toggleTheme = function () {
    const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    try {
        window.localStorage.setItem(THEME_KEY, next);
    } catch (e) {
        // ignore
    }
    applyTheme(next);
};

initTheme();

/** Global confirm modal for forms with attribute data-bc-confirm="message" */
document.addEventListener('DOMContentLoaded', () => {
    const bs = typeof window !== 'undefined' ? window.bootstrap : undefined;
    if (!bs || !bs.Modal) {
        return;
    }
    const modalEl = document.getElementById('bcConfirmModal');
    if (!modalEl) {
        return;
    }
    const modal = bs.Modal.getOrCreateInstance(modalEl);
    let pendingForm = null;

    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-bc-confirm')) {
            return;
        }
        e.preventDefault();
        pendingForm = form;
        const msgEl = document.getElementById('bcConfirmMessage');
        if (msgEl) {
            msgEl.textContent = form.getAttribute('data-bc-confirm') || 'Continue?';
        }
        modal.show();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        pendingForm = null;
    });

    const okBtn = document.getElementById('bcConfirmOk');
    if (okBtn) {
        okBtn.addEventListener('click', () => {
            if (!pendingForm) {
                modal.hide();
                return;
            }
            const form = pendingForm;
            pendingForm = null;
            modal.hide();
            form.submit();
        });
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.data('layoutShell', () => ({
        sidebarOpen: false,
        chatUnread: 0,
        pollTimer: null,
        initLayout() {
            this.refreshUnread();
            this.pollTimer = setInterval(() => this.refreshUnread(), 45000);
        },
        refreshUnread() {
            if (!window.axios) {
                return;
            }
            window.axios
                .get('/messages/unread-count')
                .then(({ data }) => {
                    this.chatUnread = data.count || 0;
                })
                .catch(() => {});
        },
    }));

    Alpine.data('chatThread', (partnerId, initialMessages, initialLastId) => ({
        messages: Array.isArray(initialMessages) ? initialMessages : [],
        lastId: initialLastId || 0,
        pollTimer: null,
        startPolling() {
            this.$nextTick(() => this.scrollDown());
            this.pollTimer = setInterval(() => this.pull(), 7000);
        },
        scrollDown() {
            const el = this.$refs.scrollBox;
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        },
        async pull() {
            if (!window.axios) {
                return;
            }
            try {
                const { data } = await window.axios.get(`/messages/${partnerId}/sync`, {
                    params: { after: this.lastId },
                });
                const incoming = data.messages || [];
                if (incoming.length) {
                    this.messages = [...this.messages, ...incoming];
                    incoming.forEach((m) => {
                        this.lastId = Math.max(this.lastId, m.id);
                    });
                    this.$nextTick(() => this.scrollDown());
                }
            } catch (e) {
                // Intentionally quiet — no technical details to users
            }
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();
