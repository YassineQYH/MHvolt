document.addEventListener('DOMContentLoaded', () => {

    const slider1 = document.getElementById("glide1");

    // ✅ On ne monte Glide QUE si l'élément existe
    if (!slider1) return;

    new Glide(slider1, {
        type: "carousel",
        startAt: 0,
        perView: 1, // attention : typo corrigée (perview → perView)
        animationDuration: 800,
        animationTimingFunc: "linear",
    }).mount();

});
