document.addEventListener("DOMContentLoaded", () => {
    const cartButtons = document.querySelectorAll(".ajax-cart");

    cartButtons.forEach(btn => {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            event.stopPropagation(); // Empêche le menu déroulant de se refermer

            const productId = this.dataset.id;
            const productType = this.dataset.type;
            const action = this.dataset.action;

            let url = null;
            if (action === "increase") url = "/cart/ajax/increase";
            if (action === "decrease") url = "/cart/ajax/decrease";
            if (action === "delete") url = "/cart/ajax/delete";
            if (!url) return;

            // 🔹 Mise à jour visuelle instantanée côté client
            const row = this.closest(".navbar-cart-product");
            if (row) {
                const quantityElement = row.querySelector(".cart-quantity");
                const priceElement = row.querySelector(".cart-total");

                if (quantityElement && priceElement) {
                    let currentQty = parseInt(quantityElement.textContent) || 0;

                    if (action === "increase") currentQty++;
                    if (action === "decrease") currentQty = currentQty > 1 ? currentQty - 1 : 0;
                    if (action === "delete") currentQty = 0;

                    quantityElement.textContent = currentQty;

                    // Récupération directe du prix TTC unitaire
                    const unitPriceTTC = parseFloat(priceElement.dataset.priceTtc) || 0;
                    if (unitPriceTTC > 0) {
                        priceElement.textContent = (unitPriceTTC * currentQty).toFixed(2).replace(".", ",") + " €";
                    }

                    if (currentQty === 0) {
                        row.remove();
                    }
                }
            }

            // 🔹 Requête AJAX vers le backend
            fetch(url, {
                method: "POST",
                body: JSON.stringify({ id: productId, type: productType }),
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(data => {
                updateMiniCart(data, productId, productType);
            })
            .catch(err => console.error("❌ Erreur AJAX mini-panier :", err));
        });
    });
});

function updateMiniCart(data, productId, productType) {
    if (!data) return;

    // Mise à jour de la pastille du compteur sur le bouton panier de la navbar
    const cartCountBadge = document.querySelector(".cart-badge-count");
    if (cartCountBadge && typeof data.total_quantity !== "undefined") {
        if (data.total_quantity > 0) {
            cartCountBadge.textContent = data.total_quantity;
            cartCountBadge.style.display = "inline-flex";
        } else {
            cartCountBadge.style.display = "none";
        }
    }

    // Mise à jour de la ligne produit si elle existe encore
    const element = document.querySelector(`.ajax-cart[data-id="${productId}"][data-type="${productType}"]`);
    if (element) {
        const row = element.closest(".navbar-cart-product");
        if (row && Array.isArray(data.items)) {
            const item = data.items.find(i => i.id == productId && i.type === productType);
            if (item) {
                const quantityElement = row.querySelector(".cart-quantity");
                const priceTotalElement = row.querySelector(".cart-total");

                if (quantityElement) quantityElement.textContent = item.quantity;
                if (priceTotalElement && typeof item.price_total_ttc !== "undefined") {
                    priceTotalElement.textContent = item.price_total_ttc.toFixed(2).replace(".", ",") + " €";
                }
            }
        }
    }

    // Mise à jour du total global affiché en bas du dropdown
    const totalGlobal = document.querySelector(".mini-cart-total, .navbar-cart-total strong");
    if (totalGlobal && typeof data.total !== "undefined") {
        totalGlobal.textContent = data.total.toFixed(2).replace(".", ",") + " €";
    }
}
