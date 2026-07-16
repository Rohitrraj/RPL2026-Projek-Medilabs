/**
 * MediLabs public and patient interactions.
 *
 * Berisi interaksi presentasional untuk shell, form pasien, reservasi,
 * riwayat, dan salin kode. Tidak mengubah route atau proses bisnis Laravel.
 */

const setExpandedState = (trigger, panel, expanded) => {
    trigger.setAttribute('aria-expanded', String(expanded));
    panel.hidden = !expanded;
};

const initializeMobileNavigation = () => {
    const trigger = document.querySelector('[data-public-nav-toggle]');
    const panel = document.querySelector('[data-public-mobile-nav]');

    if (!(trigger instanceof HTMLButtonElement) || !(panel instanceof HTMLElement)) {
        return;
    }

    trigger.addEventListener('click', () => {
        const expanded = trigger.getAttribute('aria-expanded') === 'true';
        setExpandedState(trigger, panel, !expanded);
    });

    panel.querySelectorAll('a, button[type="submit"]').forEach((item) => {
        item.addEventListener('click', () => {
            setExpandedState(trigger, panel, false);
        });
    });

    window.addEventListener('resize', () => {
        if (window.matchMedia('(min-width: 901px)').matches) {
            setExpandedState(trigger, panel, false);
        }
    });
};

const initializeAccountMenus = () => {
    const menus = document.querySelectorAll('[data-public-account]');

    menus.forEach((menu) => {
        const trigger = menu.querySelector('[data-public-account-trigger]');
        const panel = menu.querySelector('[data-public-account-menu]');

        if (!(trigger instanceof HTMLButtonElement) || !(panel instanceof HTMLElement)) {
            return;
        }

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();

            const expanded = trigger.getAttribute('aria-expanded') === 'true';
            setExpandedState(trigger, panel, !expanded);
        });

        panel.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        document.addEventListener('click', () => {
            setExpandedState(trigger, panel, false);
        });
    });
};

const initializeShellKeyboardControls = () => {
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('[data-public-account-trigger]').forEach((trigger) => {
            const menu = trigger.closest('[data-public-account]');
            const panel = menu?.querySelector('[data-public-account-menu]');

            if (trigger instanceof HTMLButtonElement && panel instanceof HTMLElement) {
                setExpandedState(trigger, panel, false);
            }
        });

        const navTrigger = document.querySelector('[data-public-nav-toggle]');
        const navPanel = document.querySelector('[data-public-mobile-nav]');

        if (navTrigger instanceof HTMLButtonElement && navPanel instanceof HTMLElement) {
            setExpandedState(navTrigger, navPanel, false);
        }
    });
};

const initializeConfirmationForms = () => {
    document.querySelectorAll('[data-confirm-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.addEventListener('submit', (event) => {
            const message = form.dataset.confirmMessage
                || 'Lanjutkan tindakan ini?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
};

const copyText = async (text) => {
    if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(text);
        return;
    }

    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
};

const initializeCopyButtons = () => {
    document.querySelectorAll('[data-copy-target]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        button.addEventListener('click', async () => {
            const selector = button.dataset.copyTarget;
            const target = selector ? document.querySelector(selector) : null;

            if (!(target instanceof HTMLElement)) {
                return;
            }

            const text = target.textContent?.trim();

            if (!text) {
                return;
            }

            try {
                await copyText(text);

                const scope = button.closest('.ml-reservation-code')
                    || button.parentElement;
                const feedback = scope?.querySelector('[data-copy-feedback]');

                if (feedback instanceof HTMLElement) {
                    feedback.hidden = false;

                    window.setTimeout(() => {
                        feedback.hidden = true;
                    }, 1800);
                }
            } catch (error) {
                console.error('Kode reservasi gagal disalin.', error);
            }
        });
    });
};

const formatReservationDate = (value) => {
    if (!value) {
        return 'Belum dipilih';
    }

    const [year, month, day] = value.split('-').map(Number);

    if (!year || !month || !day) {
        return value;
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(new Date(year, month - 1, day));
};

const initializeReservationSummary = () => {
    const form = document.querySelector('[data-reservation-form]');

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const service = form.querySelector('[data-reservation-service]');
    const date = form.querySelector('[data-reservation-date]');
    const time = form.querySelector('[data-reservation-time]');

    const serviceSummary = document.querySelector(
        '[data-reservation-summary-service]',
    );
    const priceSummary = document.querySelector(
        '[data-reservation-summary-price]',
    );
    const dateSummary = document.querySelector(
        '[data-reservation-summary-date]',
    );
    const timeSummary = document.querySelector(
        '[data-reservation-summary-time]',
    );

    const updateService = () => {
        if (!(service instanceof HTMLSelectElement)) {
            return;
        }

        const selectedOption = service.selectedOptions[0];
        const selected = Boolean(selectedOption?.value);

        if (serviceSummary instanceof HTMLElement) {
            serviceSummary.textContent = selected
                ? selectedOption.dataset.serviceName || selectedOption.textContent?.trim() || '-'
                : 'Belum dipilih';
        }

        if (priceSummary instanceof HTMLElement) {
            priceSummary.textContent = selected
                ? selectedOption.dataset.servicePrice || '-'
                : '-';
        }
    };

    const updateDate = () => {
        if (date instanceof HTMLInputElement && dateSummary instanceof HTMLElement) {
            dateSummary.textContent = formatReservationDate(date.value);
        }
    };

    const updateTime = () => {
        if (time instanceof HTMLSelectElement && timeSummary instanceof HTMLElement) {
            timeSummary.textContent = time.value
                ? `${time.value} WIB`
                : 'Belum dipilih';
        }
    };

    service?.addEventListener('change', updateService);
    date?.addEventListener('change', updateDate);
    time?.addEventListener('change', updateTime);

    updateService();
    updateDate();
    updateTime();
};

const initializeCharacterCounters = () => {
    document.querySelectorAll('[data-character-count]').forEach((field) => {
        if (!(field instanceof HTMLTextAreaElement)) {
            return;
        }

        const container = field.closest('.ml-public-field');
        const output = container?.querySelector('[data-character-count-value]');

        if (!(output instanceof HTMLElement)) {
            return;
        }

        const update = () => {
            output.textContent = String(field.value.length);
        };

        field.addEventListener('input', update);
        update();
    });
};

const initializePublicForms = () => {
    document.querySelectorAll('[data-public-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.addEventListener('submit', () => {
            if (!form.checkValidity()) {
                return;
            }

            const button = form.querySelector('[data-public-submit]');
            const label = form.querySelector('[data-public-submit-label]');

            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.disabled = true;
            button.classList.add('is-loading');
            button.setAttribute('aria-busy', 'true');

            if (label instanceof HTMLElement) {
                label.textContent = 'Memproses...';
            }
        });
    });
};

const initializePublicShell = () => {
    initializeMobileNavigation();
    initializeAccountMenus();
    initializeShellKeyboardControls();
    initializeConfirmationForms();
    initializeCopyButtons();
    initializeReservationSummary();
    initializeCharacterCounters();
    initializePublicForms();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePublicShell);
} else {
    initializePublicShell();
}
