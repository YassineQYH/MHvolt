document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.getElementById('ea-main-image');
    if (!mainImage) return;

    document.querySelectorAll('.ea-thumbnail').forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            mainImage.src = thumbnail.dataset.full;
        });
    });
});
