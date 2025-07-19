<h1>Your email has been verified!</h1>
<br>
<?php if (isset($_GET['message'])): ?>
    <h4><?php echo htmlspecialchars($_GET['message']); ?></h4>
<?php else: ?>
    <h4>Welcome to Venusia. Click on the user icon to sign in.</h4>
<?php endif; ?>