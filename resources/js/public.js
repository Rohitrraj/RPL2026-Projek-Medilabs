/**
 * MediLabs public and patient shell interactions.
 *
 * Hanya menangani navigasi mobile dan menu akun. Tidak mengubah route,
 * autentikasi, atau proses bisnis Laravel.
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

const initializePublicShell = () => {
    initializeMobileNavigation();
    initializeAccountMenus();
    initializeShellKeyboardControls();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePublicShell);
} else {
    initializePublicShell();
}
