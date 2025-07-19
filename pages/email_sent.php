<h1>Your verification link has been sent!</h1>
<br>
<?php if (isset($_GET['message'])): ?>
    <h4><?php echo htmlspecialchars($_GET['message']); ?></h4>
<?php else: ?>
    <h4>Check your inbox to verify your account.</h4>
<?php endif; ?>