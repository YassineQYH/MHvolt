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

    promoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const code = promoCodeInput.value.trim();

        if (!code) {
            promoMessage.textContent = 'Veuillez saisir un code promo.';
            return;
        }

        fetch(promoForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ promo_code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    if (promoMessage) promoMessage.textContent = data.error;
                    if (reductionPromo) reductionPromo.textContent = '';
                    if (totalRemiseContainer) totalRemiseContainer.style.display = 'none';
                    if (totalOriginal) totalOriginal.style.textDecoration = '';
                    if (appliedPromoCode) appliedPromoCode.textContent = '';
                } else {
                    if (promoMessage) promoMessage.textContent = '';
                    if (reductionPromo) reductionPromo.textContent = '-' + data.discount.toFixed(2).replace('.', ',') + ' €';
                    if (totalOriginal) totalOriginal.style.textDecoration = 'line-through';
                    if (totalRemise) totalRemise.textContent = data.totalAfterPromo.toFixed(2).replace('.', ',') + ' €';
                    if (totalRemiseContainer) totalRemiseContainer.style.display = 'block';
                    if (appliedPromoCode) appliedPromoCode.textContent = code;
                }
            })
            .catch(err => {
                console.error(err);
                if (promoMessage) promoMessage.textContent = 'Une erreur est survenue.';
            });
    });
});