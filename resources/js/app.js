import './bootstrap';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.swiper', {
        slidesPerView: 'auto',
        spaceBetween: 8,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        grabCursor: true,
        loop: false, // bisa kamu ubah ke true kalau mau infinite scroll
    });
});
