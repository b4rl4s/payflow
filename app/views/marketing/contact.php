<?php
$pageTitle = 'Contact PayFlow sales & support';
ob_start();
?>
<div class="row g-5">
    <div class="col-lg-7">
        <h1 class="h3 mb-3">Tell us where your close process stalls.</h1>
        <p class="text-secondary">Deployment specialists respond within one business day. For sensitive production incidents escalate through <strong>Incident bridge</strong> referenced on your Order Form.</p>
        <dl class="row small">
            <dt class="col-sm-4">Corporate HQ</dt>
            <dd class="col-sm-8">85 Broad Street · New York, NY · visitor Wi-Fi onboarding via guest.payflowcorp.net</dd>
            <dt class="col-sm-4">Customer success</dt>
            <dd class="col-sm-8"><a href="mailto:success@payflow.co">success@payflow.co</a> · SLA varies by tier</dd>
            <dt class="col-sm-4">Integrations engineers</dt>
            <dd class="col-sm-8"><a href="mailto:integrations-api@payflow.co">integrations-api@payflow.co</a></dd>
            <dt class="col-sm-4">Press</dt>
            <dd class="col-sm-8"><a href="mailto:press@payflow.co">press@payflow.co</a></dd>
        </dl>
    </div>
    <div class="col-lg-5">
        <form class="border rounded p-3 bg-light-subtle">
            <div class="mb-3">
                <label class="form-label">Work email</label>
                <input type="email" class="form-control" placeholder="you@company.com">
            </div>
            <div class="mb-3">
                <label class="form-label">What are you evaluating?</label>
                <textarea class="form-control" rows="4" placeholder="Give us elevator context — entity count, banks, timelines."></textarea>
            </div>
            <button class="btn btn-dark w-100" type="button" disabled="">Form wiring ships after compliance review</button>
            <p class="small text-muted mt-2 mb-0">Call +1 (332) 555-0182 for active prospects with NDA on file.</p>
        </form>
    </div>
</div>
<!-- integrations dry-run memo linked from changelog — /docs/integration-paths.txt -->
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
