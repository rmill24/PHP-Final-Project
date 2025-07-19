<h1>ERROR :(</h1>
<?php if (isset($_GET['message'])): ?>
    <h4><?php echo htmlspecialchars($_GET['message']); ?></h4>
<?php else: ?>
    <h4>An error occurred. We're sorry about the inconvenience.</h4>
<?php endif; ?>

<?php if (strpos($_GET['message'] ?? '', 'verification') !== false || strpos($_GET['message'] ?? '', 'expired') !== false): ?>
    <h5>Need a new verification link?</h5>
    <div class="container">
        <form id="resendForm">
            <input type="email" name="email" id="emailInput" placeholder="Enter your email address" required>
            <button type="submit" id="resendBtn">Resend Link</button>
        </form>
        
        <!-- Inline feedback message -->
        <div id="feedbackMessage" style="margin-top: 15px; padding: 10px; border-radius: 4px; display: none;"></div>
    </div>

    <?php if (isset($_GET['cooldown']) && $_GET['cooldown'] > 0): ?>
        <div id="cooldownMessage" style="margin-top: 10px; color: #666;">
            Please wait <span id="countdown">
                <?php
                $cooldown = $_GET['cooldown'];
                $minutes = floor($cooldown / 60);
                $seconds = $cooldown % 60;
                echo $minutes > 0 ?
                    $minutes . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT) :
                    $seconds . " seconds";
                ?>
            </span> before requesting again.
        </div>
    <?php endif; ?>
<?php endif; ?>