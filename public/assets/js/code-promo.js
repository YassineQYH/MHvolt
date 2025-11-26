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

            if (data.error) {
                promoMessage.textContent = data.error;
                reductionPromo.textContent = '';
                totalRemiseContainer.style.display = 'none';
                totalOriginal.style.textDecoration = '';
                appliedPromoCode.textContent = '';
            } else {
                promoMessage.textContent = '';
                reductionPromo.textContent = '-' + data.discount.toFixed(2).replace('.', ',') + ' €';
                totalOriginal.style.textDecoration = 'line-through';
                totalRemise.textContent = data.totalAfterPromo.toFixed(2).replace('.', ',') + ' €';
                totalRemiseContainer.style.display = 'block';
                appliedPromoCode.textContent = code;
            }
        } catch (err) {
            console.error(err);
            promoMessage.textContent = 'Une erreur est survenue.';
        }
    }

    promoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const code = promoCodeInput.value.trim();
        if (!code) {
            promoMessage.textContent = 'Veuillez saisir un code promo.';
            return;
        }
        applyPromo(code);
    });

    // Réappliquer le code promo si présent au chargement
    const currentCode = promoCodeInput.value.trim();
    if (currentCode) {
        applyPromo(currentCode);
    }

    // Fonction globale pour réappliquer la promo après modification du panier
    window.reapplyPromo = function() {
        const code = promoCodeInput.value.trim();
        if (code) {
            applyPromo(code);
        } else {
            reductionPromo.textContent = '';
            totalRemiseContainer.style.display = 'none';
            totalOriginal.style.textDecoration = '';
            appliedPromoCode.textContent = '';
        }
    };

    // ---- Modification du panier ----
    // Pour chaque bouton d'action (add, decrease, delete)
    document.querySelectorAll('.cart-action').forEach(button => {
        button.addEventListener('click', function() {
            const id = button.dataset.id;
            const type = button.dataset.type;
            const action = button.dataset.action; // ex: add, decrease, delete

            fetch(`/cart/${action}/${type}/${id}`) // adapte cette URL selon ton backend
                .then(res => res.json())
                .then(data => {
                    // Mise à jour du mini-panier si tu as un callback
                    if (typeof updateMiniCart === 'function') updateMiniCart(data);

                    // Recalcule automatiquement la promo
                    window.reapplyPromo();
                })
                .catch(err => console.error(err));
        });
    });
});
