<?php
$pageTitle = 'Invoice overview';
$pageMeta = 'Public summary';

ob_start();
$invoice = $invoice ?? [];
?>
<h1 class="h5 mb-2">Invoice <?= htmlspecialchars((string) ($invoice['reference_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
<p class="text-secondary small"><?= htmlspecialchars((string) ($invoice['customer_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
<p class="small"><span class="badge text-bg-light border"><?= htmlspecialchars((string) ($invoice['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
<?php if (!empty($verbose)): ?> · verbose trace requested<?php endif; ?></p>
<p class="small text-muted mb-0">For balance detail and remittance advice, authorized users should sign in to the workspace.</p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
