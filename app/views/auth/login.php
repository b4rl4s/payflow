<?php
$pageTitle = 'Sign in · PayFlow';
$pageMeta = 'Secure workspace access.';
$resume = isset($_GET['resume']) ? (string) $_GET['resume'] : '';
if ($resume !== '' && !str_starts_with($resume, '/')) {
    $resume = '';

}
ob_start();

?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="border rounded-4 shadow-sm p-4 bg-white">
            <h1 class="h4 mb-3">Welcome back</h1>
            <?php if (!empty($_SESSION['flash_errors'])): ?>
                <div class="alert alert-warning small"><?= htmlspecialchars(implode(' ', $_SESSION['flash_errors']), ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash_errors']); ?></div>
            <?php endif; ?>
            <form method="post" action="/login">
                <?php if ($resume !== ''): ?>
                    <input type="hidden" name="resume" value="<?= htmlspecialchars($resume, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Work email</label>
                    <input required class="form-control" type="email" autocomplete="username" name="email" placeholder="you@company.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Passphrase</label>
                    <input required class="form-control" type="password" autocomplete="current-password" name="password">
                </div>
                <button class="btn btn-dark w-100 py-2" type="submit">Continue</button>
                <p class="small text-muted mt-3 mb-0">New workspace provisioning is invite-only · contact your PayFlow deployment lead.</p>
            </form>
        </div>
        <!-- auth bundle still loads legacy beacon from <?= htmlspecialchars(pf_site('cdn.payflow.local', '/_archive/assets/js/auth-telemetry.v0.js'), ENT_QUOTES, 'UTF-8') ?> (remove Q3) -->
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
