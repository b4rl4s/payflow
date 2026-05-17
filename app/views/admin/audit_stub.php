<?php
$pageTitle = 'Audit consoles';
ob_start();

?>
<h1 class="h4 mb-3">Audit trail</h1>
<p class="small text-secondary">Primary exports move to SOC bucket + signed URLs. Older shell alias pointed engineers at JSON mirrors — still visible in bookmarks as <code>/api/admin/audit</code>.</p>
<div class="border rounded bg-white p-3 small">
    <p class="mb-2 fw-semibold">Quick links</p>
    <ul class="mb-0">
        <li><code>POST /support/bundle</code> (VPN) · frozen after RBAC uplift</li>
        <li>Read-only feed (deprecated): alternate JSON path referenced in Signals rollout doc</li>
        <li>Archive exports: treasury service only after June cutoff per platform note</li>
    </ul>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
