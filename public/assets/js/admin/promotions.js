document.addEventListener("DOMContentLoaded", function() {
    const targetField = document.querySelector("[name='Promotion[targetType]']");
    if (!targetField) return;

    const getFieldRow = (fieldName) => {
        const input = document.querySelector(`[name='Promotion[${fieldName}]']`);
        return input ? input.closest('.form-group, .field') : null;
    };

    const fields = {
        category_access: getFieldRow('categoryAccess'),
        product: getFieldRow('product'),
        product_list: getFieldRow('products')
    };

    const allRows = Object.values(fields).filter(Boolean);

    // Initial setup: hide all
    allRows.forEach(row => {
        row.style.transition = "max-height 0.3s ease, opacity 0.3s ease";
        row.style.overflow = "hidden";
        row.style.opacity = "0";
        row.style.maxHeight = "0";
        row.style.display = "none";
    });

    let animationTimeouts = [];

    const showRows = (rowsToShow) => {
        // Clear previous animations
        animationTimeouts.forEach(t => clearTimeout(t));
        animationTimeouts = [];

        let delay = 0;
        rowsToShow.forEach(row => {
            if (!row) return;
            const t = setTimeout(() => {
                row.style.display = "block";
                row.style.maxHeight = row.scrollHeight + "px";
                row.style.opacity = "1";
            }, delay);
            animationTimeouts.push(t);
            delay += 100; // cascade delay
        });
    };

    const hideRows = (rowsToHide) => {
        let delay = 0;
        rowsToHide.forEach(row => {
            if (!row) return;
            const t = setTimeout(() => {
                row.style.maxHeight = "0";
                row.style.opacity = "0";
                setTimeout(() => { row.style.display = "none"; }, 300); // hide after animation
            }, delay);
            animationTimeouts.push(t);
            delay += 50; // smaller cascade for hiding
        });
    };

    const updateFields = () => {
        const type = targetField.value;

        // Determine which fields to show
        const toShow = [];
        if (type === "category_access") toShow.push(fields.category_access);
        if (type === "product") toShow.push(fields.product);
        if (type === "product_list") toShow.push(fields.product_list);

        // Fields not to show
        const toHide = allRows.filter(r => !toShow.includes(r));

        hideRows(toHide);
        showRows(toShow);
    };

    targetField.addEventListener("change", updateFields);
    updateFields(); // initial
});
