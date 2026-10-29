document.addEventListener("DOMContentLoaded", () => {
    const targetField = document.querySelector("[name='Promotion[targetType]']");
    if (!targetField) return;

    const getFieldRow = (fieldName) => {
        const input = document.querySelector(`[name^='Promotion[${fieldName}]']`);
        return input ? input.closest('.form-group, .field, .form-row') : null;
    };

    const fields = {
        category_access: getFieldRow('categoryAccess'),
        product: getFieldRow('product'),
        product_list: getFieldRow('products')
    };

    const allRows = Object.values(fields).filter(Boolean);

    // Prépare les styles de transition
    allRows.forEach(row => {
        row.style.transition = "max-height 0.25s ease, opacity 0.2s ease";
        row.style.overflow = "hidden";
    });

    let animationTimeouts = [];

    const showRow = (row) => {
        if (!row) return;
        row.style.display = "";
        // Force reflow pour garantir la prise en compte du scrollHeight
        const height = row.scrollHeight;
        row.style.maxHeight = height + "px";
        row.style.opacity = "1";
    };

    const hideRow = (row) => {
        if (!row) return;
        row.style.maxHeight = "0";
        row.style.opacity = "0";
        setTimeout(() => {
            if (row.style.opacity === "0") {
                row.style.display = "none";
            }
        }, 250);
    };

    const updateFields = () => {
        animationTimeouts.forEach(t => clearTimeout(t));
        animationTimeouts = [];

        const type = targetField.value;

        const toShow = [];
        if (type === "category_access") toShow.push(fields.category_access);
        if (type === "product") toShow.push(fields.product);
        if (type === "product_list") toShow.push(fields.product_list);

        allRows.forEach(row => {
            if (toShow.includes(row)) {
                showRow(row);
            } else {
                hideRow(row);
            }
        });
    };

    targetField.addEventListener("change", updateFields);
    targetField.addEventListener("input", updateFields);

    // Initialisation immédiate sans clignotement
    updateFields();
});
