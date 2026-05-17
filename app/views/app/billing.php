<?php
$pageTitle = 'Billing · PayFlow';
ob_start();

?>
<h1 class="h4 mb-3">Billing &amp; invoices from PayFlow</h1>
<p class="text-secondary small">Stripe-backed card on file · ACH debit available for Growth+ with treasury review.</p>
<div class="row g-3">
    <div class="col-md-6">
        <div class="border rounded p-3 h-100">
            <h2 class="h6">Current period</h2>
            <p class="small text-muted mb-1">Estimated May invoice</p>
            <div class="fs-4 fw-semibold">$24,890</div>
            <p class="small mb-0">Includes platform fee + metering for instant payouts · detail PDF emails from <code>billing+noreply@payflow.co</code></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 h-100">
            <h2 class="h6">Historical exports</h2>
            <p class="small text-muted">Tax packets stored in SOC-compliant bucket mirrored to archival export lane.</p>
            <button class="btn btn-sm btn-dark" type="button" onclick="alert('SOC hold — request via success manager.')">Download SOX packet</button>
        </div>
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
