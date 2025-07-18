window.addEventListener('load', () => {
  document.querySelectorAll('.loading').forEach(el => el.classList.remove('loading'));
});

document.addEventListener('DOMContentLoaded', () => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.timeline-content').forEach(el => observer.observe(el));
});