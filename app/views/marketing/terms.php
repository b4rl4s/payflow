<?php
$pageTitle = 'Terms of service — PayFlow';
ob_start();
?>
<h1 class="h3">Terms of service</h1>
<p class="text-secondary small">Last updated April 18, 2026.</p>
<div class="text-secondary small lh-lg">
    <p>By accessing PayFlow services you agree to the Master Services Agreement executed at onboarding, including acceptable use, export control compliance, and incident notification windows.</p>
    <p>Preview environments (for example routes served from <code>staging.payflow.local</code>) may contain transient data unrelated to production ledgers and are not billing determinative.</p>
    <p>API usage caps, rate limiting, and deprecation windows are communicated via changelog and Partner emails. Legacy routes may continue to resolve for backwards compatibility.</p>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
