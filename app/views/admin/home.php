<?php
$pageTitle = 'Operations console';
ob_start();

?>
<h1 class="h4 mb-3">Today’s operating picture</h1>
<div class="row g-3">
    <div class="col-md-4">
        <div class="border rounded p-3 h-100 bg-white">
            <div class="text-muted small">ACH queue depth</div>
            <div class="fs-3 fw-semibold">812</div>
            <div class="small text-success">Within SLO</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3 h-100 bg-white">
            <div class="text-muted small">Partner incident</div>
            <div class="fs-6 fw-semibold">Bank stack B · monitoring</div>
            <a class="small" href="<?= htmlspecialchars(pf_site('status.payflow.local', '/'), ENT_QUOTES, 'UTF-8') ?>">Open status</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3 h-100 bg-white">
            <div class="text-muted small">Support escalations</div>
            <div class="fs-3 fw-semibold">3</div>
            <a class="small" href="/support/tickets">Queue</a>
        </div>
    </div>
</div>
<p class="small text-muted mt-4 mb-0">Legacy migration notes: <code>/_migration/migration-sheet.md</code> · shadow webhook config on CDN private path · <a href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">integration-paths memo</a> (draft).</p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
