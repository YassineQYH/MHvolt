document.addEventListener('DOMContentLoaded', () => {
    window.showRegisterView = function () {
        const loginSec = document.getElementById('loginSection');
        const regSec = document.getElementById('registerSection');
        const title = document.getElementById('authModalTitle');

        if (loginSec && regSec) {
            loginSec.classList.add('d-none');
            regSec.classList.remove('d-none');
            if (title) title.textContent = "Inscription";
        }
    };

    window.showLoginView = function () {
        const loginSec = document.getElementById('loginSection');
        const regSec = document.getElementById('registerSection');
        const title = document.getElementById('authModalTitle');

        if (loginSec && regSec) {
            regSec.classList.add('d-none');
            loginSec.classList.remove('d-none');
            if (title) title.textContent = "Se connecter";
        }
    };

    window.togglePasswordVisibility = function (inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = 'Masquer';
        } else {
            input.type = 'password';
            btn.textContent = 'Afficher';
        }
    };

    // Si une erreur d'inscription existe dans les flashes
    const modalEl = document.getElementById('authModal');
    if (modalEl && modalEl.dataset.hasRegisterError === 'true') {
        window.showRegisterView();
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }
});
