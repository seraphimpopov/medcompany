document.addEventListener("DOMContentLoaded", function() {
    const menu = document.querySelector('.menu__body'),
        burger = document.querySelector('.burger'),
        overlay = document.querySelector('.overlay')

    const lockScroll = () => {
        document.body.classList.add('lock')
    }

    const unlockScroll = () => {
        document.body.classList.remove('lock')
    }

    burger.addEventListener('click', () => {
        menu.classList.add('open');
        overlay.classList.add('open');
        lockScroll();
    });

    overlay.addEventListener('click', () => {
        menu.classList.remove('open');
        overlay.classList.remove('open');
        unlockScroll();
    });
});