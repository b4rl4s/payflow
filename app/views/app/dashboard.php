<?php
$pageTitle = 'Workspace overview';
ob_start();
$u = ($ctx ?? [])['user'] ?? null;
?>
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Morning, <?= htmlspecialchars((string) ($u['name'] ?? 'there'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-secondary mb-0">Northbeam Roasters Cooperative · enterprise plan · New York close calendar</p>
    </div>
    <a class="btn btn-outline-secondary" href="/export?type=forecast">Download forecast rollforward</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Open invoices</div>
                <div class="display-6 fw-semibold">$482.5k</div>
                <div class="small text-success">On track vs. last close</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Liquidity headroom</div>
                <div class="display-6 fw-semibold">$12.1M</div>
                <div class="small text-muted">Reflects partner bank cutoff 5:10pm ET</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Automation coverage</div>
                <div class="display-6 fw-semibold">86%</div>
                <div class="small text-muted">ACH + instant split</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Upcoming disbursements</div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li>Fulton Street Baking Co. — $128,400 ACH · clears May 19</li>
                    <li>Harbor Freight &amp; Supply — $482,500 wire · pending compliance check</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Workspace shortcuts</div>
            <div class="list-group list-group-flush small">
                <a class="list-group-item list-group-item-action" href="/analytics">Open collections intelligence beta</a>
                <a class="list-group-item list-group-item-action" href="/dashboard/archive">Archived invoice lane (read-only)</a>
                <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars(pf_site('payflow.local', '/changelog'), ENT_QUOTES, 'UTF-8') ?>">Review platform changelog</a>
            </div>
        </div>
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
