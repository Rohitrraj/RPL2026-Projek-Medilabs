/**
 * MediLabs Authentication Interactions
 *
 * Berisi interaksi frontend khusus halaman autentikasi.
 * Tidak mengubah proses autentikasi Laravel.
 */

const initializePasswordToggles = () => {
    const toggleButtons = document.querySelectorAll(
        '[data-password-toggle]',
    );

    toggleButtons.forEach((button) => {
        const inputId = button.dataset.passwordToggle;
        const input = document.getElementById(inputId);
        const icon = button.querySelector('[data-password-icon]');

        if (!(input instanceof HTMLInputElement) || !icon) {
            return;
        }

        button.addEventListener('click', () => {
            const passwordIsHidden = input.type === 'password';
            const passwordWillBeVisible = passwordIsHidden;

            input.type = passwordWillBeVisible
                ? 'text'
                : 'password';

            icon.classList.toggle(
                'bi-eye',
                !passwordWillBeVisible,
            );

            icon.classList.toggle(
                'bi-eye-slash',
                passwordWillBeVisible,
            );

            const accessibleLabel = passwordWillBeVisible
                ? 'Sembunyikan password'
                : 'Tampilkan password';

            button.setAttribute(
                'aria-label',
                accessibleLabel,
            );

            button.setAttribute(
                'title',
                accessibleLabel,
            );

            button.setAttribute(
                'aria-pressed',
                String(passwordWillBeVisible),
            );
        });
    });
};

const initializeAuthForms = () => {
    const forms = document.querySelectorAll(
        '[data-auth-form]',
    );

    forms.forEach((form) => {
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.addEventListener('submit', () => {
            if (!form.checkValidity()) {
                return;
            }

            const submitButton = form.querySelector(
                '[data-submit-button]',
            );

            const submitLabel = form.querySelector(
                '[data-submit-label]',
            );

            const submitLoading = form.querySelector(
                '[data-submit-loading]',
            );

            if (!(submitButton instanceof HTMLButtonElement)) {
                return;
            }

            submitButton.disabled = true;
            submitButton.classList.add('is-loading');
            submitButton.setAttribute('aria-busy', 'true');

submitLoading?.classList.remove('d-none');
submitLoading?.classList.add('d-inline-flex');
        });
    });
};

const initializeAuthPage = () => {
    initializePasswordToggles();
    initializeAuthForms();
};

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeAuthPage,
    );
} else {
    initializeAuthPage();
}