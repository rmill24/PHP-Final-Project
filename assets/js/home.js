document.addEventListener("DOMContentLoaded", function () {
  const carouselItems = document.querySelectorAll(".carousel-item");
  const indicators = document.querySelectorAll(".carousel-indicator");
  const carousel = document.querySelector(".carousel");
  let currentSlide = 0;
  const slideCount = carouselItems.length;

  function showSlide(index) {
    carouselItems.forEach((item) => item.classList.remove("active"));
    indicators.forEach((indicator) => indicator.classList.remove("active"));
    carouselItems[index].classList.add("active");
    indicators[index].classList.add("active");
    currentSlide = index;
  }

  let slideInterval = setInterval(() => {
    currentSlide = (currentSlide + 1) % slideCount;
    showSlide(currentSlide);
  }, 5000);

  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => {
      clearInterval(slideInterval);
      showSlide(index);
      slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
      }, 5000);
    });
  });

  if (carousel) {
    carousel.addEventListener("mouseenter", () => clearInterval(slideInterval));
    carousel.addEventListener("mouseleave", () => {
      slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
      }, 5000);
    });
  }
});
