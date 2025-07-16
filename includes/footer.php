<!-- footer.php -->
<div class="footer-main-container">
    <footer>
    <div class="footer-container">
        <!-- Column 1: Logo and Tagline -->
        <div class="footer-about">
        <div class="logo-footer">
            <img src="assets/img/venusia_logo.png" alt="Venusia Icon">
            <span class="venusia-footer-text">VENUSIA</span>
        </div>


        <p><em>Luxury redefined for the modern woman.</em></p>
        </div>

        <!-- Column 2: Explore Links -->
        <div class="footer-links">
        <h3>Explore</h3>
        <ul>
            <li><a href="index.php?page=home">Home</a></li>
            <li><a href="index.php?page=store">Collection</a></li>
            <li><a href="index.php?page=store">New Arrivals</a></li>
        </ul>
        </div>

        <!-- Column 3: Information Links -->
        <div class="footer-links">
        <h3>Information</h3>
        <ul>
            <li><a href="index.php?page=about">About Us</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms & Conditions</a></li>
        </ul>
        </div>

        <!-- Column 4: Newsletter Signup -->
        <div class="footer-contact">
        <h3>Connect</h3>
        <p>Subscribe to receive updates on new collections, exclusive offers, and the stories behind our designs</p>
        <form>
            <input type="email" placeholder="Your email">
            <button type="submit">SEND</button>
        </form>
        </div>
    </div>

    <!-- Copyright -->
    <div class="copyright">
        This website is for educational purposes only and is a requirement for a final project. Venusia is a fictional brand. <br>
        &copy; 2025 Venusia. All rights reserved.
    </div>
    </footer>
</div> 

  <!-- Global JS -->
  <script src="assets/js/global.js"></script>

  <!-- Page-specific JS -->
  <?php if (file_exists("assets/js/{$current_page}.js")): ?>
    <script src="assets/js/<?= $current_page ?>.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <?php endif; ?>
</body>
</html>
