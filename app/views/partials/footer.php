<?php
$ctx = $ctx ?? [];
$showCookie = !($_COOKIE['pf_consent'] ?? false);
?>
<footer class="border-top mt-5 py-4 text-secondary small">
    <div class="container-xl">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="fw-semibold text-body">PayFlow</div>
                <div>North America · EMEA · APAC processing regions</div>
                <div class="mt-2">Security contact: <a href="mailto:security@payflow.co">security@payflow.co</a></div>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold text-body">Product</div>
                <ul class="list-unstyled mb-0">
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('payflow.local', '/pricing'), ENT_QUOTES, 'UTF-8') ?>">Pricing</a></li>
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('payflow.local', '/developers'), ENT_QUOTES, 'UTF-8') ?>">API</a></li>
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('status.payflow.local', '/'), ENT_QUOTES, 'UTF-8') ?>">Status</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold text-body">Policies</div>
                <ul class="list-unstyled mb-0">
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('payflow.local', '/privacy'), ENT_QUOTES, 'UTF-8') ?>">Privacy</a></li>
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('payflow.local', '/terms'), ENT_QUOTES, 'UTF-8') ?>">Terms</a></li>
                    <li><a class="link-secondary" href="<?= htmlspecialchars(pf_site('payflow.local', '/security'), ENT_QUOTES, 'UTF-8') ?>">Trust</a></li>
                </ul>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
            <div>© <?= date('Y') ?> PayFlow Financial Corp. All rights reserved.</div>
            <div class="d-flex flex-wrap gap-3 align-items-center">
                <a class="link-secondary text-decoration-none" style="font-size:.78rem;" href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">Integration index</a>
                <span class="text-muted">SOC 2 Type II in progress · PCI DSS SAQ-A alignment</span>
            </div>
        </div>
    </div>
</footer>
<?php if ($showCookie): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" role="region" aria-label="Cookie banner">
    <div class="toast show shadow" role="dialog" aria-live="polite">
        <div class="toast-header">
            <strong class="me-auto">Payments experience cookies</strong>
            <small>Preference center</small>
        </div>
        <div class="toast-body small">
            We use functional cookies required for treasury controls and analytics with PayFlow Signals.
            Signals beacon currently dual-writes via <span class="text-muted">insights.payflow.io</span>; EU residency toggle ships this quarter.
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="pfSaveConsent()">Necessary only</button>
                <button type="button" class="btn btn-sm btn-dark" onclick="pfSaveConsent(true)">Allow analytics</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<script defer src="/assets/js/cookie_banner.js?v=20260418"></script>
<?php $scriptSrc = (($ctx ?? [])['analytics_script'] ?? ''); ?>
<?php if (is_string($scriptSrc) && $scriptSrc !== ''): ?>
<script defer src="<?= htmlspecialchars($scriptSrc, ENT_QUOTES, 'UTF-8') ?>" crossorigin></script>
<?php endif; ?>
<script defer src="/assets/js/portal.widgets.js?v=202604"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
