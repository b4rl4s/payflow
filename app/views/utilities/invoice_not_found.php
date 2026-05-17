<?php
$pageTitle = 'Invoice lookup';
ob_start();

?>
<h1 class="h4">We couldn’t reconcile that invoice</h1>
<p class="text-secondary small">Reference <code><?= htmlspecialchars((string) ($term ?? ''), ENT_QUOTES, 'UTF-8') ?></code> isn’t resolving on this edge. Workspace customers should open the ledger view on <a href="<?= htmlspecialchars(pf_site('app.payflow.local', '/invoices'), ENT_QUOTES, 'UTF-8') ?>">app.payflow.local</a>.</p>
<p class="small text-muted">Finance desk hotline mirrors through <strong>signals.payflow.local</strong> when enabled for your tenancy.</p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
