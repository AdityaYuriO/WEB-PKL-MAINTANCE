document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('#carouselExample');
    const carouselInstance = new bootstrap.Carousel(carousel, {
        interval: 3000,
        wrap: true
    });
});