// Carrousel d'images pour les chambres
document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.room-carousel');
    
    carousels.forEach(carousel => {
        const images = carousel.querySelectorAll('.carousel-image');
        const prevBtn = carousel.querySelector('.carousel-btn-prev');
        const nextBtn = carousel.querySelector('.carousel-btn-next');
        const indicators = carousel.querySelectorAll('.carousel-indicator');
        let currentIndex = 0;
        
        if (images.length <= 1) {
            // Masquer les boutons si une seule image
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            if (indicators.length > 0) {
                indicators.forEach(ind => ind.style.display = 'none');
            }
            return;
        }
        
        function showImage(index) {
            // Masquer toutes les images
            images.forEach((img, i) => {
                img.classList.remove('active');
                if (indicators[i]) {
                    indicators[i].classList.remove('active');
                }
            });
            
            // Afficher l'image courante
            images[index].classList.add('active');
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }
            
            currentIndex = index;
        }
        
        function nextImage() {
            const nextIndex = (currentIndex + 1) % images.length;
            showImage(nextIndex);
        }
        
        function prevImage() {
            const prevIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(prevIndex);
        }
        
        // Event listeners pour les boutons
        if (nextBtn) {
            nextBtn.addEventListener('click', nextImage);
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', prevImage);
        }
        
        // Event listeners pour les indicateurs
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showImage(index));
        });
        
        // Navigation au clavier
        carousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        });
        
        // Initialiser avec la première image
        showImage(0);
    });
});
