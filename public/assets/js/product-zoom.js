document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialisation du Zoom interactif
    const initZoom = (containerId, imageId) => {
        const container = document.getElementById(containerId);
        const img = document.getElementById(imageId);
        let isZoomed = false;

        if (container && img) {
            container.addEventListener('click', (e) => {
                const rect = container.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                isZoomed = !isZoomed;

                if (isZoomed) {
                    img.style.transformOrigin = `${x}% ${y}%`;
                    img.style.transform = 'scale(2.3)';
                    container.style.cursor = 'zoom-out';
                } else {
                    img.style.transformOrigin = 'center center';
                    img.style.transform = 'scale(1)';
                    container.style.cursor = 'zoom-in';
                }
            });

            container.addEventListener('mousemove', (e) => {
                if (isZoomed) {
                    const rect = container.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    img.style.transformOrigin = `${x}% ${y}%`;
                }
            });
        }
    };

    initZoom('trottZoomContainer', 'mainProductImage');
    initZoom('accessoryZoomContainer', 'mainAccessoryImage');

    // 2. Gestion des miniatures de galerie
    const setupGalleryThumbs = (btnClass, mainImgId, containerId) => {
        document.querySelectorAll(btnClass).forEach((btn) => {
            btn.addEventListener('click', function () {
                const src = this.getAttribute('data-img-src');
                const mainImg = document.getElementById(mainImgId);
                const container = document.getElementById(containerId);

                if (!mainImg || !src) return;

                mainImg.style.transform = 'scale(1)';
                if (container) container.style.cursor = 'zoom-in';

                mainImg.style.opacity = '0.3';
                setTimeout(() => {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                }, 150);

                document.querySelectorAll(btnClass).forEach((el) => {
                    el.classList.remove('border-dark');
                    el.classList.add('border-light-subtle');
                });
                this.classList.remove('border-light-subtle');
                this.classList.add('border-dark');
            });
        });
    };

    setupGalleryThumbs('.product-thumb-btn', 'mainProductImage', 'trottZoomContainer');
    setupGalleryThumbs('.accessory-thumb-btn', 'mainAccessoryImage', 'accessoryZoomContainer');
});
