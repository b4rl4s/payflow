<?php
$pageTitle = 'PayFlow — Treasury automation for modern finance teams';
$pageMeta = 'Invoice lifecycle, settlement intelligence, and cash visibility in one platform.';
ob_start();
?>
<div class="row align-items-center g-4 py-3">
    <div class="col-lg-6">
        <p class="text-uppercase small text-muted letter-spacing fw-semibold">Operating system for B2B payables</p>
        <h1 class="display-6 fw-semibold mb-3">Close faster without losing control of disbursements.</h1>
        <p class="lead text-secondary">PayFlow centralizes invoicing, settlement rails, reconciliation signals, and board-ready analytics for teams juggling multiple entities.</p>
        <div class="d-flex gap-3 flex-wrap">
            <a class="btn btn-lg btn-dark" href="/contact">Talk to sales</a>
            <a class="btn btn-lg btn-outline-dark" href="<?= htmlspecialchars(pf_site('app.payflow.local', '/login'), ENT_QUOTES, 'UTF-8') ?>">Customer login</a>
        </div>
        <div class="mt-4 small text-muted">Trusted by breakout brands in specialty retail, food supply, and light manufacturing · processing via partner banks · real-time alerting through <span class="text-reset">signals.payflow.local</span> (VPC access)</div>
    </div>
    <div class="col-lg-6">
        <div class="rounded-4 border bg-light p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-muted small">Settlement forecast</div>
                    <div class="fs-4 fw-semibold text-success">+$4.82M<span class="text-muted fs-6 fw-normal"> next 14d</span></div>
                </div>
                <span class="badge text-bg-secondary">SOC review</span>
            </div>
            <div class="ratio ratio-4x3 bg-white border rounded mb-3 d-flex align-items-center justify-content-center text-muted">
                treasury preview · redacted counterparties
            </div>
            <div class="small text-muted">Demo data shown. Production deployments include multi-entity hierarchies with delegated approvals.</div>
        </div>
    </div>
</div>
<section class="row g-4 py-5 border-top">
    <div class="col-md-4">
        <h3 class="h5">Invoices that stay audit-friendly</h3>
        <p class="text-secondary small">Immutable change history, vendor portal, and automated nudges tied to your working capital policy.</p>
    </div>
    <div class="col-md-4">
        <h3 class="h5">Payments that respect policy</h3>
        <p class="text-secondary small">Route ACH, domestic wire, and instant payouts with guardrails that mirror your bank covenants.</p>
    </div>
    <div class="col-md-4">
        <h3 class="h5">Analytics your board actually reads</h3>
        <p class="text-secondary small">Drill from cash positioning to invoice aging without exporting stale spreadsheets.</p>
    </div>
</section>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
