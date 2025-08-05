window.addEventListener('load', function () {
    document.body.classList.add('loaded');

    // Menu mobile
    const burgerMenu = document.querySelector('.burger-menu');
    const navMenu = document.querySelector('.nav-menu');
    const menuItems = document.querySelectorAll('.menu > li > a[href="#"]');

    let noScrollCount = 0; // Compteur pour gérer la classe no-scroll

    const addNoScroll = () => {
        noScrollCount++;
        if (noScrollCount === 1) {
            document.body.classList.add('no-scroll');
        }
    };

    const removeNoScroll = () => {
        if (noScrollCount > 0) {
            noScrollCount--;
            if (noScrollCount === 0) {
                document.body.classList.remove('no-scroll');
            }
        }
    };

    burgerMenu.addEventListener('click', () => {
        burgerMenu.classList.toggle('active');
        navMenu.classList.toggle('active');

        if (burgerMenu.classList.contains('active')) {
            addNoScroll();
        } else {
            removeNoScroll();
        }
    });

    menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                item.parentElement.classList.toggle('active');
            }
        });
    });

    document.addEventListener('click', (e) => {
        const isClickInsideNav = navMenu.contains(e.target);
        const isClickOnBurger = burgerMenu.contains(e.target);
        const isClickOnLightboxTrigger = e.target.closest('.lightbox-trigger') !== null;
        const isClickInsideLightbox = lightbox.contains(e.target);
    
        if (!isClickInsideNav && !isClickOnBurger && !isClickOnLightboxTrigger && !isClickInsideLightbox) {
            navMenu.classList.remove('active');
            burgerMenu.classList.remove('active');
            removeNoScroll();
            document.querySelectorAll('.menu > li').forEach(li => li.classList.remove('active'));
        }
    });
    
    // Lightbox
    const lightbox = document.querySelector('.lightbox');
    const lightboxClose = document.querySelector('.lightbox-close');
if (lightboxClose) {
    lightboxClose.addEventListener('click', () => {
        lightbox.classList.remove('active');
        removeNoScroll();
    })};
if (lightbox) {
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.remove('active');
            removeNoScroll();
        }
    })};

    // Empêcher le défilement de l'arrière-plan lorsque la lightbox est active
    const openLightbox = () => {
        lightbox.classList.add('active');
        addNoScroll();
    };

    // Ajoutez un écouteur pour ouvrir la lightbox si nécessaire
    document.querySelectorAll('.lightbox-trigger').forEach(trigger => {
        trigger.addEventListener('click', openLightbox);
    });
});

// Fonction de dézoom maximal
function forceFullDezoom() {
    const viewport = document.querySelector('meta[name="viewport"]');
    if (!viewport) return;

    // Reset du contenu du viewport
    viewport.setAttribute('content', '');

    // Application du dézoom maximal (empêche le zoom)
    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');

    // Double application pour assurer la prise en compte
    setTimeout(() => {
        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
    }, 100);

    // Réactivation du zoom après stabilisation
    setTimeout(() => {
        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, user-scalable=yes');
    }, 700);
}

// Déclenchement avec délai après changement d'orientation
let orientationTimeout;
const orientationEvents = ['orientationchange', 'resize'];

orientationEvents.forEach(evt => {
    window.addEventListener(evt, () => {
        clearTimeout(orientationTimeout);
        orientationTimeout = setTimeout(() => {
            if (window.innerWidth <= 1500) {
                forceFullDezoom();
            }
        }, 800); // Délai pour laisser le temps à l’orientation de s’ajuster
    });
});

// Application initiale au chargement
document.addEventListener('DOMContentLoaded', () => {
    if (window.innerWidth <= 1500) {
        forceFullDezoom();
    }
});
