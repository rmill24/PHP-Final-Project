// Wait for the entire page to load
window.addEventListener('load', () => {
  // Select all elements with the 'loading' class
  const loadingElements = document.querySelectorAll('.loading');

  loadingElements.forEach(el => {
    // Remove the 'loading' class to trigger CSS transition
    el.classList.remove('loading');
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const timelineContents = document.querySelectorAll('.timeline-content');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        observer.unobserve(entry.target); // Stop observing once animated
      }
    });
  }, { threshold: 0.1 });

  timelineContents.forEach(el => observer.observe(el));
});
