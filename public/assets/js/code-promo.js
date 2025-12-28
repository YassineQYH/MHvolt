document.addEventListener('DOMContentLoaded', function() {

    const promoForm = document.getElementById('promoForm');
    if (!promoForm) return;

    const promoCodeInput = document.getElementById('promoCode');
    const promoMessage = document.getElementById('promoMessage');
    const totalOriginal = document.getElementById('totalOriginal');
    const totalRemiseContainer = document.getElementById('totalRemiseContainer');
    const totalRemise = document.getElementById('totalRemise');
    const reductionPromo = document.getElementById('reductionPromo');
    const appliedPromoCode = document.getElementById('appliedPromoCode');
    const promoCodeValidInput = document.getElementById('promoCodeValid');

    //---------------------------------------------------
    // UI Utils
    //---------------------------------------------------
    function resetPromoUI() {
        promoMessage.textContent = '';
        if (reductionPromo) reductionPromo.textContent = '';
        if (totalRemiseContainer) totalRemiseContainer.style.display = 'none';
        if (totalOriginal) totalOriginal.style.textDecoration = '';
        if (appliedPromoCode) appliedPromoCode.textContent = '';
    }

    function applyPromoUI(code, discount, totalAfterPromo) {
        promoMessage.textContent = '';

        if (reductionPromo) {
            reductionPromo.textContent =
                '-' + discount.toFixed(2).replace('.', ',') + ' €';
        }

        if (totalOriginal) {
            totalOriginal.style.textDecoration = 'line-through';
        }

        if (totalRemise) {
            totalRemise.textContent =
                totalAfterPromo.toFixed(2).replace('.', ',') + ' €';
        }

        if (totalRemiseContainer) {
            totalRemiseContainer.style.display = 'block';
        }

        if (appliedPromoCode) {
            appliedPromoCode.textContent = code;
        }
    }

    //---------------------------------------------------
    // AJAX CALL
    //---------------------------------------------------
    async function applyPromo(code) {
        if (!code) return;

        try {
            const response = await fetch(promoForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ promo_code: code })
            });

            const data = await response.json();

            //------------------------------------------------
            // ERROR
            //------------------------------------------------
            if (data.error) {
                resetPromoUI();
                promoMessage.textContent = data.error;

                // ⚠️ vider le champ et marquer promo invalide
                promoCodeInput.value = '';
                if (promoCodeValidInput) promoCodeValidInput.value = "0";

                return;
            }

            //------------------------------------------------
            // SUCCESS
            //------------------------------------------------
            // 🔹 Mettre le flag promo valide
            if (promoCodeValidInput) promoCodeValidInput.value = "1";

            // 🔹 Mise à jour dynamique des totaux
            applyPromoUI(code, data.discount, data.totalAfterPromo);

            // 🔹 Si backend demande un reload (ex. remise appliquée correctement)
            if (data.reload === true) {
                // on garde le reload mais on met le flag pour ne pas réappliquer après
                sessionStorage.setItem('promoReloaded', '1');
                window.location.reload();
                return;
            }


        } catch (err) {
            console.error(err);
            promoMessage.textContent = "Une erreur est survenue.";
            resetPromoUI();
            if (promoCodeValidInput) promoCodeValidInput.value = "0";
        }
    }

    //---------------------------------------------------
    // SUBMIT DU FORMULAIRE
    //---------------------------------------------------
    promoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const code = promoCodeInput.value.trim();

        if (!code) {
            promoMessage.textContent = "Veuillez saisir un code promo.";
            resetPromoUI();
            return;
        }

        applyPromo(code);
    });

    //---------------------------------------------------
    // RÉAPPLIQUER APRÈS MODIFICATION PANIER
    //---------------------------------------------------
    window.reapplyPromo = function() {
        const code = promoCodeInput.value.trim();
        const promoCodeValid = promoCodeValidInput?.value === "1";

        if (!code || !promoCodeValid) {
            resetPromoUI();
            return;
        }

        applyPromo(code);
    };

    //---------------------------------------------------
    // BOUTONS + / - / SUPPRIMER DU PANIER
    //---------------------------------------------------
    document.querySelectorAll('.cart-action').forEach(button => {
        button.addEventListener('click', function() {
            const id = button.dataset.id;
            const type = button.dataset.type;
            const action = button.dataset.action;

            fetch(`/cart/${action}/${id}/${type}`)
                .then(res => res.json())
                .then(data => {
                    if (typeof updateMiniCart === 'function') {
                        updateMiniCart(data);
                    }

                    // ✅ recalcul promo après MAJ panier
                    window.reapplyPromo();
                })
                .catch(err => console.error(err));
        });
    });

    //---------------------------------------------------
    // ✅ RÉAPPLICATION AU CHARGEMENT (uniquement si promo valide)
    //---------------------------------------------------
    const currentCode = promoCodeInput.value.trim();
    const promoCodeValid = promoCodeValidInput?.value === "1";

    // ✅ on ne réapplique que si le code promo est valide ET qu'on n'a pas encore reloadé
    if (currentCode && promoCodeValid && !promoMessage.textContent && !sessionStorage.getItem('promoReloaded')) {
        applyPromo(currentCode).then(() => {
            // on marque que le reload est déjà fait
            sessionStorage.setItem('promoReloaded', '1');
        });
    }


});
