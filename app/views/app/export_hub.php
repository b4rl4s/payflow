<?php
$pageTitle = 'Export center';
ob_start();
$t = $type ?? '';
?>
<h1 class="h4 mb-2">Export center</h1>
<p class="small text-secondary mb-3">Generate statement-of-record extracts. Large jobs stream through the async worker pool.</p>

<?php if (!empty($hints)): ?>
<div class="alert alert-warning small">
    <div class="fw-semibold">Operational notes</div>
    <pre class="small mb-0 mt-2 bg-transparent border-0"><?= htmlspecialchars(print_r($hints, true), ENT_QUOTES, 'UTF-8') ?></pre>
</div>
<?php endif; ?>

<?php if ($t !== ''): ?>
<p class="small">Active filter: <code><?= htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8') ?></code></p>
<?php endif; ?>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h2 class="h6">Quick exports</h2>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm btn-outline-dark" href="/export?type=invoices">Invoice CSV</a>
            <a class="btn btn-sm btn-outline-dark" href="/export?type=forecast">Forecast workbook</a>
            <a class="btn btn-sm btn-outline-dark" href="/export?type=audit">Audit evidence pack</a>
            <a class="btn btn-sm btn-outline-secondary" href="/v2/export/preview.csv">Preview slice (beta)</a>
        </div>
    </div>
</div>

<div class="border rounded bg-white">
<table class="table table-sm mb-0">
    <thead class="table-light"><tr><th>ID</th><th>Reference</th><th>Customer</th></tr></thead>
    <tbody class="small">
    <?php foreach ($records ?? [] as $row): ?>
        <tr>
            <td><?= (int) $row['id'] ?></td>
            <td><code><?= htmlspecialchars((string) $row['reference_code'], ENT_QUOTES, 'UTF-8') ?></code></td>
            <td><?= htmlspecialchars((string) $row['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
