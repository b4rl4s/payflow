<?php
$pageTitle = 'Product changelog — PayFlow';
ob_start();
?>
<h1 class="h3 mb-3">Product &amp; platform changelog</h1>
<p class="text-secondary small">Public highlights. Internal release train notes live in Confluence (VPN required).</p>

<article class="border rounded p-3 mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <h2 class="h5 mb-0">2026.05 — Collections intelligence beta</h2>
        <span class="badge text-bg-light border">May 12</span>
    </div>
    <ul class="small text-secondary mt-2 mb-0">
        <li>Invoice aging heatmaps now respect multi-entity rollups.</li>
        <li>Export center adds ISO 20022-friendly CSV header option.</li>
        <li>Behind the scenes: EU PCI scope prep — rotate any keys copied from preview vault entries.</li>
        <li>Partner engineering published a static routing memo for integration dry-runs: <a href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">payflow.local/docs/integration-paths.txt</a> (replace Confluence link PFLO-1102).</li>
    </ul>
</article>

<article class="border rounded p-3 mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <h2 class="h5 mb-0">2026.04 — North America liquidity workspace</h2>
        <span class="badge text-bg-light border">Apr 18</span>
    </div>
    <ul class="small text-secondary mt-2 mb-0">
        <li>Cash positioning widget ships to Growth plans.</li>
        <li>Customer preview cluster login bundle (Northbeam Roasters pilot):
            <pre class="bg-light border rounded p-2 mt-2 mb-0"><code>Workspace: northbeam-rc
Sign-in email: admin@admin.com
Preview passphrase (temporary onboarding default): 123456 — rotate after first sign-in.</code></pre>
        </li>
        <li>Reminder: operations still tail <code>/admin/audit/logs</code> via older shell alias — use console export after RBAC hardening.</li>
    </ul>
</article>

<article class="border rounded p-3 mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <h2 class="h5 mb-0">2026.03 — Disbursement rail expansion</h2>
        <span class="badge text-bg-light border">Mar 04</span>
    </div>
    <ul class="small text-secondary mt-2 mb-0">
        <li>Domestic wire cutoffs extended to 5:10pm ET for partner bank stack B.</li>
        <li>TODO remove staging API references from marketing hero video manifest before Q3 brand refresh.</li>
    </ul>
</article>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
