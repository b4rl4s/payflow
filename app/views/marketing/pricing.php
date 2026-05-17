<?php
$pageTitle = 'Pricing — PayFlow';
$pageMeta = 'Plans for growth-stage finance teams.';
ob_start();
?>
<div class="row">
    <div class="col-lg-8">
        <h1 class="h2 mb-3">Transparent plans for operator-led finance teams</h1>
        <p class="text-secondary">Every tier ships with SSO/SAML backlog support, granular roles, SOC 2 attestation roadmap, and a dedicated onboarding pod until go-live.</p>
    </div>
</div>
<div class="row g-4 py-4">
    <div class="col-md-4">
        <div class="border rounded p-4 h-100 shadow-sm">
            <h3 class="h5">Starter</h3>
            <p class="small text-muted">Up to three entities · USD primary</p>
            <div class="display-6 fw-semibold mb-3">$499<span class="fs-6 text-muted"> / mo</span></div>
            <ul class="small text-secondary">
                <li>Inbound invoices · ACH out</li>
                <li>CSV &amp; NetSuite taps</li>
                <li>Email support SLA 8h weekdays</li>
            </ul>
            <button class="btn btn-outline-secondary w-100" type="button" disabled="">Contact sales · waitlist NA</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-4 h-100 border-dark shadow-sm position-relative">
            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill text-bg-dark">Most common</span>
            <h3 class="h5 mt-2">Growth</h3>
            <p class="small text-muted">Multi-currency rails · SSO</p>
            <div class="display-6 fw-semibold mb-3">Custom</div>
            <ul class="small text-secondary">
                <li>Liquidity workspaces</li>
                <li>Advanced analytics cohorts</li>
                <li>Regional processing add-ons · EU/APAC previews</li>
            </ul>
            <a class="btn btn-dark w-100" href="/contact">Schedule architecture review</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-4 h-100 shadow-sm">
            <h3 class="h5">Enterprise</h3>
            <p class="small text-muted">Dedicated success + policy engineering</p>
            <div class="display-6 fw-semibold mb-3">Let’s scope</div>
            <ul class="small text-secondary">
                <li>Bring-your-own HSM integrations</li>
                <li>Private processing regions</li>
                <li>White-glove cutover playbook</li>
            </ul>
            <a class="btn btn-outline-dark w-100" href="mailto:billing@payflow.co">billing@payflow.co</a>
        </div>
    </div>
</div>
<!-- pricing experiment mirror still live on staging.payflow.local/pricing?cohort=q2 — remove after uplift read -->
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
