<?php
$pageTitle = 'PayFlow Status';
$pageMeta = 'Incident and uptime communication';
ob_start();

?>
<h1 class="h3 mb-1">Systems operational</h1>
<p class="text-secondary small">Last refresh · <?= htmlspecialchars(date('g:i A T'), ENT_QUOTES, 'UTF-8') ?></p>

<div class="border rounded mb-4">
    <div class="p-3 border-bottom fw-semibold">Active incidents</div>
    <div class="p-3">
        <p class="mb-2 small"><span class="badge text-bg-secondary me-2">Monitoring</span> Elevated ACH retry noise on Bank Stack B (US)</p>
        <p class="text-muted small mb-0">Mitigation underway since 06:41 UTC · next update targeting 09:30 UTC · internal mirror JSON at <a href="/.well-known/status.json">/.well-known/status.json</a></p>
    </div>
</div>

<section class="mb-4">
    <h2 class="h6 text-uppercase text-muted">Recent history</h2>
    <ul class="list-unstyled small text-secondary mb-0">
        <li class="py-2 border-bottom">Apr 29 — partial delays on webhook delivery · resolved 47m</li>
        <li class="py-2 border-bottom">Apr 11 — dashboards latency · traced to warehouse maintenance window · resolved</li>
        <li class="py-2">Mar 07 — CDN replica rotation on <code>cdn.payflow.local</code> · no customer-facing errors</li>
    </ul>
</section>
<section class="mb-4 small text-secondary">
    <h2 class="h6 text-uppercase text-muted">Mirrors &amp; engineering</h2>
    <p class="mb-1">Webhook shadow config JSON: <a href="<?= htmlspecialchars(pf_site('cdn.payflow.local', '/assets/private/webhooks-shadow.json'), ENT_QUOTES, 'UTF-8') ?>">/assets/private/webhooks-shadow.json</a></p>
    <p class="mb-0">Partner routing memo (draft): <a href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">payflow.local/docs/integration-paths.txt</a></p>
</section>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
