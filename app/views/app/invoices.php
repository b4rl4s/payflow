<?php
$pageTitle = 'Invoices';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Invoices</h1>
    <a class="btn btn-sm btn-outline-dark" href="/export">Export</a>
</div>
<div class="table-responsive border rounded bg-white">
<table class="table table-sm table-hover align-middle mb-0">
    <thead class="table-light"><tr><th>Reference</th><th>Customer</th><th>Status</th><th>Issued</th><th class="text-end">Amount</th></tr></thead>
    <tbody class="small">
    <?php foreach ($records ?? [] as $row): ?>
        <tr>
            <td><code><?= htmlspecialchars((string) $row['reference_code'], ENT_QUOTES, 'UTF-8') ?></code></td>
            <td><?= htmlspecialchars((string) $row['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge text-bg-light border"><?= htmlspecialchars((string) $row['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
            <td><?= htmlspecialchars((string) $row['issued_on'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="text-end font-monospace"><?= number_format(((int) $row['amount_cents']) / 100, 2) ?> <?= htmlspecialchars((string) $row['currency'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<!-- deep link still supported: /invoice?id= for customer lookup modals -->
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
