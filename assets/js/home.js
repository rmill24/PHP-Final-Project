// Carousel Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const carouselItems = document.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.carousel-indicator');
            let currentSlide = 0;
            const slideCount = carouselItems.length;
            
            // Function to show a specific slide
            function showSlide(index) {
                carouselItems.forEach(item => item.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));
                
                carouselItems[index].classList.add('active');
                indicators[index].classList.add('active');
                currentSlide = index;
            }
            
            // Auto-advance slides every 5 seconds
            function autoAdvance() {
                currentSlide = (currentSlide + 1) % slideCount;
                showSlide(currentSlide);
            }
            
            let slideInterval = setInterval(autoAdvance, 5000);
            
            // Add click event to indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(slideInterval);
                    showSlide(index);
                    slideInterval = setInterval(autoAdvance, 5000);
                });
            });
            
            // Pause on hover
            const carousel = document.querySelector('.carousel');
            carousel.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            carousel.addEventListener('mouseleave', () => {
                slideInterval = setInterval(autoAdvance, 5000);
            });
        });