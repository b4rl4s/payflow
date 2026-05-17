<?php
$pageTitle = 'Developer API — PayFlow';
ob_start();
?>
<h1 class="h4 mb-3">Build on the PayFlow disbursements substrate</h1>
<p class="text-secondary small">Public gateway host: <code><?= htmlspecialchars(pf_origin('api.payflow.local'), ENT_QUOTES, 'UTF-8') ?></code> · JSON requests · TLS in production.</p>

<div class="accordion my-4" id="developerAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c1">Authentication</button></h2>
        <div id="c1" class="accordion-collapse collapse show">
            <div class="accordion-body small text-secondary">
                Use organization-scoped API keys rotated every 90 days. Keys are exchanged through the workspace console (<code>Settings → Developers</code>).
                <pre class="bg-light border rounded p-2 mt-2"><code>Authorization: Bearer sk_live_********A7k9&#10;X-PayFlow-Organization: northbeam-rc</code></pre>
                <!-- older mobile SDK still pings http(s)://beta.payflow.local/mobile/ping for feature gate — deprecate FY26-Q1 -->
                <!-- Sandbox keys mirror from dev-api.payflow.local ONLY when corp VPN posture is GREEN -->
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">Core endpoints</button></h2>
        <div id="c2" class="accordion-collapse collapse">
            <div class="accordion-body small">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Method</th><th>Route</th><th>Notes</th></tr></thead>
                    <tbody class="text-secondary">
                        <tr><td><code>GET</code></td><td><code>/v1/invoices</code></td><td>Pagination via <code>cursor</code></td></tr>
                        <tr><td><code>POST</code></td><td><code>/v1/invoices/import</code></td><td>Idempotent ingest</td></tr>
                        <tr><td><code>POST</code></td><td><code>/v1/charges/import</code></td><td>Legacy alias still honored</td></tr>
                        <tr><td><code>GET</code></td><td><code>/v1/export/summary.csv</code></td><td>Requires treasurer role</td></tr>
                        <tr><td><code>GET</code></td><td><code>/legacy/billing/passive</code></td><td>Deprecated shim — remove after treasury cutover ticket PFLO-9821</td></tr>
                    </tbody>
                </table>
                <p class="text-muted mt-2 mb-0">Partner-only feeds also hang off <code>admin.payflow.local/_migration/migration-sheet.md</code> for phased customer migrations.</p>
                <p class="text-muted mt-2 mb-0">Partners: draft routing memo for summit dry-run — <a href="<?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?>">integration-paths.txt</a> · disclosure pointer: <a href="<?= htmlspecialchars(pf_site('payflow.local', '/.well-known/security.txt'), ENT_QUOTES, 'UTF-8') ?>">security.txt</a></p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">Webhooks</button></h2>
        <div id="c3" class="accordion-collapse collapse">
            <div class="accordion-body small text-secondary">
                Delivery signing uses rotating HMAC secrets. Sample payload for regression tests:
                <code>/webhooks/test/payload.json</code> on the API host.
                Shadow traffic configuration is documented in <code>cdn.payflow.local/assets/private/webhooks-shadow.json</code> (internal distribution only).
            </div>
        </div>
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
