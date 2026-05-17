<?php
$pageTitle = 'Invoice reconcile (support)';
$pageMeta = 'Verbose lookup';
ob_start();

$invoice = $invoice ?? [];
?>
<div class="alert alert-warning small">
    Support-only verbose response. Heads-up mobile team: still shipping legacy <code>GET /invoice?id={numeric}</code> without bearer context.
</div>
<dl class="row small">
    <dt class="col-sm-3">Reference</dt>
    <dd class="col-sm-9"><code><?= htmlspecialchars((string) ($invoice['reference_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?></code></dd>
    <dt class="col-sm-3">Customer</dt>
    <dd class="col-sm-9"><?= htmlspecialchars((string) ($invoice['customer_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
    <dt class="col-sm-3">Status</dt>
    <dd class="col-sm-9"><?= htmlspecialchars((string) ($invoice['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?> · archived: <?= ((int) ($invoice['archived'] ?? 0)) === 1 ? 'yes' : 'no' ?></dd>
    <dt class="col-sm-3">Requested raw</dt>
    <dd class="col-sm-9 font-monospace"><?= htmlspecialchars((string) ($requested ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
</dl>
<?php if (!empty($verbose)): ?>
<?php $probe = htmlspecialchars(json_encode([
    'internal' => 'naive-id',
    'shadow_exports' => pf_site('api.payflow.local', '/api/internal/reports'),
    'audit_tail' => pf_site('admin.payflow.local', '/audit?filter=invoice'),
], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), ENT_QUOTES, 'UTF-8'); ?>
<pre class="bg-light border rounded p-2 small text-muted mb-0"><?= $probe ?></pre>
<?php endif; ?>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
