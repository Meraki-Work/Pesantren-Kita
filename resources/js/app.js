import './bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".carousel-slide");
    const dots = document.querySelectorAll(".carousel-dot");
    let current = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle("opacity-100", i === index);
            slide.classList.toggle("opacity-0", i !== index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle("bg-white", i === index);
            dot.classList.toggle("bg-gray-400", i !== index);
        });
    }

    // Auto play
    setInterval(() => {
        current = (current + 1) % slides.length;
        showSlide(current);
    }, 4000);

    // Manual dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            current = index;
            showSlide(current);
        });
    });

    // Show first slide
    showSlide(current);
});
