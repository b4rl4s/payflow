<?php
$pageTitle = 'Transactions';
ob_start();
?>
<h1 class="h4 mb-3">Transactions</h1>
<div class="table-responsive border rounded bg-white">
<table class="table table-sm mb-0 align-middle">
    <thead class="table-light"><tr><th>Reference</th><th>Method</th><th>Status</th><th>Settled</th><th class="text-end">Amount</th></tr></thead>
    <tbody class="small">
    <?php foreach ($records ?? [] as $row): ?>
        <tr>
            <td><?= htmlspecialchars((string) ($row['provider_ref'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $row['method'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $row['status'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) ($row['settled_on'] ?? 'pending'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="text-end font-monospace"><?= number_format(((int) $row['amount_cents']) / 100, 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
