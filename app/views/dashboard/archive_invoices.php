<?php
$pageTitle = 'Archived invoices';
ob_start();

?>
<h1 class="h4 mb-3">Archived lane</h1>
<p class="small text-secondary">Read-only history · regulatory holds still apply · exports route through treasury console.</p>
<div class="table-responsive border rounded bg-white mt-3">
<table class="table table-sm mb-0 align-middle">
    <thead class="table-light"><tr><th>ID</th><th>Reference</th><th>Customer</th><th>Issued</th><th class="text-end">Amount</th></tr></thead>
    <tbody class="small">
    <?php foreach ($records ?? [] as $row): ?>
        <tr>
            <td><?= (int) $row['id'] ?></td>
            <td><code><?= htmlspecialchars((string) $row['reference_code'], ENT_QUOTES, 'UTF-8') ?></code></td>
            <td><?= htmlspecialchars((string) $row['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $row['issued_on'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="text-end font-monospace"><?= number_format(((int) $row['amount_cents']) / 100, 2) ?> <?= htmlspecialchars((string) $row['currency'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($records)): ?>
        <tr><td colspan="5" class="text-muted p-4">Nothing archived yet for this tenant.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
