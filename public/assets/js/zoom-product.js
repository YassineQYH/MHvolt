document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('image-zoom-overlay');
    const zoomedImage = document.getElementById('image-zoom-content');

    if (!overlay || !zoomedImage) {
        console.log('Overlay zoom manquant');
        return;
    }

    // 👉 délégation : fonctionne même quand le slider change d'image
    document.addEventListener('click', (e) => {
        const img = e.target.closest('.glide__slide--active img');

        if (!img) return;

        console.log('Zoom image:', img.src);
        zoomedImage.src = img.src;
        overlay.style.display = 'flex';
    });

    overlay.addEventListener('click', () => {
        overlay.style.display = 'none';
        zoomedImage.src = '';
    });
});
