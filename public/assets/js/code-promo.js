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
                promoMessage.textContent = data.error;
                resetPromoUI();
                return;
            }

            //------------------------------------------------
            // SUCCESS
            //------------------------------------------------
            // 🔥 Si le backend demande un reload, on recharge la page
            if (data.reload === true) {
                window.location.reload();
                return;
            }

            // Sinon mise à jour dynamique des totaux
            applyPromoUI(code, data.discount, data.totalAfterPromo);

        } catch (err) {
            console.error(err);
            promoMessage.textContent = "Une erreur est survenue.";
            resetPromoUI();
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
        if (!code) {
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
    // ✅ RÉAPPLICATION AU CHARGEMENT
    //---------------------------------------------------
    const currentCode = promoCodeInput.value.trim();
    if (currentCode) {
        applyPromo(currentCode);
    }

});
