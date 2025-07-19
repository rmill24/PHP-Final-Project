<h1>ERROR :(</h1>
<?php if (isset($_GET['message'])): ?>
    <h4><?php echo htmlspecialchars($_GET['message']); ?></h4>
<?php else: ?>
    <h4>An error occurred. We're sorry about the inconvenience.</h4>
<?php endif; ?>

<?php if (strpos($_GET['message'] ?? '', 'verification') !== false || strpos($_GET['message'] ?? '', 'expired') !== false): ?>
    <h5>Need a new verification link?</h5>
    <div class="container">
        <form action="actions/resend_verification.php" method="POST" id="resendForm">
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit" id="resendBtn">Resend Link</button>
        </form>

        <?php if (isset($_GET['cooldown']) && $_GET['cooldown'] > 0): ?>
            <div id="cooldownMessage" style="margin-top: 10px; color: #666;">
                Please wait <span id="countdown"><?php echo $_GET['cooldown']; ?></span> seconds before requesting again.
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Cooldown timer functionality
        <?php if (isset($_GET['cooldown']) && $_GET['cooldown'] > 0): ?>
            let cooldownTime = <?php echo $_GET['cooldown']; ?>;
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            const cooldownMessage = document.getElementById('cooldownMessage');

            // Disable button initially
            resendBtn.disabled = true;
            resendBtn.style.opacity = '0.5';
            resendBtn.style.cursor = 'not-allowed';

            const timer = setInterval(() => {
                cooldownTime--;
                countdown.textContent = cooldownTime;

                if (cooldownTime <= 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.style.opacity = '1';
                    resendBtn.style.cursor = 'pointer';
                    cooldownMessage.style.display = 'none';
                }
            }, 1000);
        <?php endif; ?>
    </script>
<?php endif; ?>