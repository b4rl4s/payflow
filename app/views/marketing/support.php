<?php
$pageTitle = 'Support — PayFlow';
ob_start();
?>
<h1 class="h3 mb-3">How can we help?</h1>
<div class="row g-4">
    <div class="col-md-6">
        <div class="border rounded p-3 h-100">
            <h2 class="h6">Customer workspace issues</h2>
            <p class="small text-secondary mb-2">Sign in on <code>app.payflow.local</code> and open a ticket from <strong>Settings → Support</strong>.</p>
            <p class="small text-muted mb-0">After-hours P1: page the bridge listed in your Order Form (not published here).</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 h-100">
            <h2 class="h6">API &amp; webhooks</h2>
            <p class="small text-secondary mb-2">Email <a href="mailto:integrations-api@payflow.co">integrations-api@payflow.co</a> with request IDs from response headers.</p>
            <p class="small text-muted mb-0">Regression fixtures: <code><?= htmlspecialchars(pf_site('api.payflow.local', '/webhooks/test/payload.json'), ENT_QUOTES, 'UTF-8') ?></code></p>
        </div>
    </div>
</div>
<p class="small text-muted mt-4">Status page: <a href="<?= htmlspecialchars(pf_site('status.payflow.local', '/'), ENT_QUOTES, 'UTF-8') ?>">status.payflow.local</a> · historical mirror JSON at <code><?= htmlspecialchars(pf_site('status.payflow.local', '/.well-known/status.json'), ENT_QUOTES, 'UTF-8') ?></code> (best-effort).</p>
<p class="small text-secondary mt-2 mb-0">Integration dry-run index (internal draft, same file on CDN POP): <a href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">/docs/integration-paths.txt</a></p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
