<?php
require_once __DIR__ . '/../includes/session.php';

// Redirect logged-in users to their profile page
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=user');
    exit;
}
?>

<!-- Registration Section -->
<section class="registration-section" id="register">
    <div class="registration-container">
        <div class="registration-content">
            <h2>Sign Up Now</h2>
            <p class="subtitle">A touch of elegance, just for you.</p>
            <p>Join our list and enjoy early access to new drops, exclusive styles, and timeless pieces you'll keep reaching for.</p>
        </div>

        <form class="registration-form" action="actions/register.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required placeholder="0912 3456 789">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="street">Street Address</label>
                    <input type="text" id="street" name="street" placeholder="123 Main Street" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Manila" required>
                </div>
                <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" placeholder="Metro Manila" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="zipCode">ZIP Code</label>
                    <input type="text" id="zipCode" name="zipCode" placeholder="1000" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country" required>
                        <option value="">Select Country</option>
                        <option value="Philippines" selected>Philippines</option>
                        <option value="United States">United States</option>
                        <option value="Canada">Canada</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Australia">Australia</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <div id="emailError" class="field-error"></div>
                </div>
            </div>
            <div id="formErrors">
                <?php if (isset($_GET['error'])): ?>
                    <p class="server-error">❌ <?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</section>