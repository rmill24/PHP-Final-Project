document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const closeMenuBtn = document.querySelector('.close-menu-btn');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const mobileMenuContainer = document.querySelector('.mobile-menu-container');

    // Toggle mobile menu
    mobileMenuToggle.addEventListener('click', function () {
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    });

    // Close menu when clicking close button or overlay
    function closeMenu() {
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Re-enable scrolling
    }

    closeMenuBtn.addEventListener('click', closeMenu);
    mobileMenuOverlay.addEventListener('click', function (e) {
        if (e.target === mobileMenuOverlay) {
            closeMenu();
        }
    });

    // Close menu when clicking on a link
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // Close menu when pressing Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('active')) {
            closeMenu();
        }
    });
});

function openModal() {
    document.body.style.overflow = 'hidden';
    document.getElementById('loginModal').classList.add('active');
    document.querySelector('.modal').classList.add('active');
}
function closeModal() {
    document.body.style.overflow = 'auto';
    document.getElementById('loginModal').classList.remove('active');
    document.querySelector('.modal').classList.remove('active');
}

function handleLogin(event) {
    event.preventDefault();

    const email = document.querySelector('.form-input[type="email"]').value;
    const password = document.querySelector('.form-input[type="password"]').value;

    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.text())
    .then(result => {
        if (result === 'success') {
            closeModal();
            location.reload();
        } else {
            alert("Login failed. Please try again.");
        }
    });
}