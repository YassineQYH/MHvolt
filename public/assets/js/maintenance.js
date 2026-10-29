document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('open-login-popup');
    const popupWrapper = document.querySelector('.wrapper');

    // Ouvrir le popup
    if (openBtn && popupWrapper) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            popupWrapper.classList.add('active-popup');
        });
    }

    // Fermer le popup
    document.addEventListener('click', (e) => {
        if (e.target.closest('.icon-close')) {
            popupWrapper?.classList.remove('active-popup');
        }
    });

    // Basculer l'affichage des mots de passe
    const setupTogglePassword = (buttonSelector, inputSelector) => {
        document.addEventListener('click', (e) => {
            if (e.target.matches(buttonSelector)) {
                const input = document.querySelector(inputSelector);
                if (input) {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    e.target.textContent = isPassword ? 'Cacher' : 'Afficher';
                }
            }
        });
    };

    setupTogglePassword('.show-password-login', '#inputPassword');
    setupTogglePassword('.show-password', '#register_password_first');
    setupTogglePassword('.show-password-retype', '#register_password_second');

    // Ouverture automatique si paramètre URL ?login=1 ou si erreur
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login') === '1' && popupWrapper) {
        popupWrapper.classList.add('active-popup');
    }
});
