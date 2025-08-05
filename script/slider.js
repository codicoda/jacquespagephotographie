document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.wrapper');
    const lightbox = document.querySelector('.lightbox');
    const lightboxImg = lightbox.querySelector('img');
    const lightboxClose = lightbox.querySelector('.lightbox-close');
    const lightboxPrev = lightbox.querySelector('.lightbox-prev');
    const lightboxNext = lightbox.querySelector('.lightbox-next');

    let currentImages;
    let currentIndex;
    let isMobile = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);

    let lightboxIsDragging = false;
    let lightboxStartX;
    let lightboxCurrentTranslate = 0;

    const applyLightboxTransform = () => {
        lightboxImg.style.maxWidth = '75%';
        lightboxImg.style.maxHeight = '75vh';
        lightboxImg.style.objectFit = 'contain';
    };

    window.addEventListener('resize', () => {
        isMobile = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);
        if (lightbox.classList.contains('active')) {
            applyLightboxTransform();
        }
    });

    sliders.forEach(wrapper => {
        const carousel = wrapper.querySelector(".carousel");
        const firstImage = carousel.querySelector("img");
        const arrowIcons = wrapper.querySelectorAll("i");

        // D'abord, supprimer les indicateurs existants s'il y en a
        const existingIndicators = wrapper.querySelector('.scroll-indicators');
        if (existingIndicators) {
            existingIndicators.remove();
        }

        // Ajout du code pour les indicateurs
        const images = carousel.querySelectorAll('img');
        const scrollIndicators = document.createElement('div');
        scrollIndicators.className = 'scroll-indicators';
        
        // Création des points indicateurs
        images.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = 'scroll-indicator';
            if (index === 0) dot.classList.add('active');
            scrollIndicators.appendChild(dot);
        });
        
        // Ajout des indicateurs après le carousel
        wrapper.appendChild(scrollIndicators);

        // Fonction pour mettre à jour les indicateurs
        const updateIndicators = () => {
            const scrollPosition = carousel.scrollLeft;
            const cardWidth = firstImage ? firstImage.clientWidth + 14 : 0;
            const currentIndex = Math.round(scrollPosition / cardWidth);
            
            scrollIndicators.querySelectorAll('.scroll-indicator').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        };

        let isDragging = false;
        let startX;
        let scrollLeft;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID = 0;
        const SWIPE_THRESHOLD = 50; // Distance minimale pour déclencher un swipe

        const toggleArrowIcons = () => {
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    const maxScroll = Math.round(carousel.scrollWidth - carousel.clientWidth);
                    arrowIcons[0].style.display = carousel.scrollLeft <= 0 ? "none" : "block";
                    arrowIcons[1].style.display = Math.round(carousel.scrollLeft) >= maxScroll ? "none" : "block";
                }, 100);
            }
        };

        const scrollCarousel = (direction) => {
            const cardWidth = firstImage.clientWidth + 14;
            const maxScroll = carousel.scrollWidth - carousel.clientWidth;
            const scrollAmount = direction === "right" ? cardWidth : -cardWidth;
            carousel.scrollLeft = Math.min(Math.max(carousel.scrollLeft + scrollAmount, 0), maxScroll);
            toggleArrowIcons();
        };

        arrowIcons.forEach((icon) => {
            icon.addEventListener("click", () => {
                const direction = icon.id === "right" ? "right" : "left";
                scrollCarousel(direction);
            });
        });

        carousel.querySelectorAll('img').forEach((img, index) => {
            img.addEventListener('click', function () {
                currentImages = Array.from(carousel.querySelectorAll('img'));
                currentIndex = index;
                lightboxImg.src = currentImages[currentIndex].dataset.full;
                lightbox.classList.add('active');
                toggleLightboxArrows();

                lightboxImg.onload = applyLightboxTransform;
            });
        });

        const handleTouchStart = (e) => {
            if (!isMobile) return;
            isDragging = true;
            startX = e.touches[0].pageX;
            scrollLeft = carousel.scrollLeft;
            carousel.style.scrollBehavior = 'auto';
        };

        const handleTouchMove = (e) => {
            if (!isDragging || !isMobile) return;
            
            const x = e.touches[0].pageX;
            const y = e.touches[0].pageY;
            
            // Calculer la différence depuis le début du toucher
            const walkX = x - startX;
            
            // Si le mouvement est plus horizontal que vertical
            if (Math.abs(walkX) > 10) {
                e.preventDefault(); // Empêcher le scroll uniquement si c'est un swipe horizontal
                carousel.scrollLeft = scrollLeft - walkX;
            }
        };

        const handleTouchEnd = () => {
            if (!isMobile) return;
            isDragging = false;
            
            const cardWidth = firstImage.clientWidth + 14;
            const currentPosition = carousel.scrollLeft;
            const targetIndex = Math.round(currentPosition / cardWidth);
            
            carousel.style.scrollBehavior = 'smooth';
            carousel.scrollTo({
                left: targetIndex * cardWidth,
                behavior: 'smooth'
            });
            
            setTimeout(() => {
                updateIndicators();
                toggleArrowIcons();
            }, 300);
        };

        // Ajouter du CSS pour la transition
        carousel.style.transition = 'transform 0.3s ease-out';

        carousel.addEventListener("touchstart", handleTouchStart, { passive: false });
        carousel.addEventListener("touchmove", handleTouchMove, { passive: false });
        carousel.addEventListener("touchend", handleTouchEnd);

        // Ajout d'un écouteur pour le scroll
        carousel.addEventListener('scroll', () => {
            requestAnimationFrame(updateIndicators);
        });

        toggleArrowIcons();
    });

    lightboxPrev.addEventListener('click', (e) => {
        e.stopPropagation();
        if (currentIndex > 0) {
            currentIndex--;
            updateLightboxImage('right');
            toggleLightboxArrows();
        }
    });

    lightboxNext.addEventListener('click', (e) => {
        e.stopPropagation();
        if (currentIndex < currentImages.length - 1) {
            currentIndex++;
            updateLightboxImage('left');
            toggleLightboxArrows();
        }
    });

    const handleLightboxTouchStart = (e) => {
        if (e.target === lightboxPrev || e.target === lightboxNext || e.target === lightboxClose) return;
        if (!isMobile) return;
        lightboxIsDragging = true;
        lightbox.classList.add("dragging");
        lightboxStartX = e.touches[0].pageX;
        lightboxCurrentTranslate = 0;
    };

    const handleLightboxTouchMove = (e) => {
        if (!lightboxIsDragging || !isMobile) return;
        
        const currentX = e.touches[0].pageX;
        const walkX = currentX - lightboxStartX;
        
        // Si le mouvement est clairement horizontal
        if (Math.abs(walkX) > 10) {
            e.preventDefault(); // Empêcher le scroll uniquement si swipe horizontal
            lightboxCurrentTranslate = (currentIndex === 0 && walkX > 0) || 
                (currentIndex === currentImages.length - 1 && walkX < 0)
                ? walkX * 0.3
                : walkX;
        }
    };

    const handleLightboxTouchEnd = () => {
        if (!isMobile || !lightboxIsDragging) return;
        lightboxIsDragging = false;
        lightbox.classList.remove("dragging");
        const threshold = window.innerWidth * 0.15;
        
        if (Math.abs(lightboxCurrentTranslate) > threshold) {
            if (lightboxCurrentTranslate > 0 && currentIndex > 0) {
                currentIndex--;
                updateLightboxImage('right');
                toggleLightboxArrows();
            } else if (lightboxCurrentTranslate < 0 && currentIndex < currentImages.length - 1) {
                currentIndex++;
                updateLightboxImage('left');
                toggleLightboxArrows();
            }
        }
        
        lightboxCurrentTranslate = 0;
    };

    lightbox.addEventListener("touchstart", handleLightboxTouchStart, { passive: false });
    lightbox.addEventListener("touchmove", handleLightboxTouchMove, { passive: false });
    lightbox.addEventListener("touchend", handleLightboxTouchEnd);

    lightboxClose.addEventListener('click', () => lightbox.classList.remove('active'));
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) lightbox.classList.remove('active');
    });

    document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('active')) {
            if (e.key === 'ArrowLeft') lightboxPrev.click();
            if (e.key === 'ArrowRight') lightboxNext.click();
            if (e.key === 'Escape') lightbox.classList.remove('active');
        }
    });

    const updateLightboxImage = (direction) => {
        const translateDirection = direction === 'left' ? -100 : 100;
        const transitionImage = document.createElement('img');
        transitionImage.src = lightboxImg.src;
        transitionImage.className = 'transitioning';
        lightbox.insertBefore(transitionImage, lightboxImg);
        requestAnimationFrame(() => {
            transitionImage.style.opacity = '0';
        });
        lightboxImg.src = currentImages[currentIndex].dataset.full;
        lightboxImg.onload = applyLightboxTransform;
        setTimeout(() => {
            if (transitionImage.parentNode) transitionImage.remove();
        }, 300);
    };

    const toggleLightboxArrows = () => {
        lightboxPrev.style.display = currentIndex === 0 ? "none" : "block";
        lightboxNext.style.display = currentIndex === currentImages.length - 1 ? "none" : "block";
    };

    // Ajout du support pour la grille
    const gridImages = document.querySelectorAll('.grid-img');
    
    gridImages.forEach((img, index) => {
        img.addEventListener('click', function () {
            currentImages = Array.from(gridImages);
            currentIndex = index;
            lightboxImg.src = this.dataset.full;
            lightbox.classList.add('active');
            toggleLightboxArrows();
            lightboxImg.onload = applyLightboxTransform;
        });
    });
});