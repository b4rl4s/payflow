<?php
$pageTitle = 'Operating report';

ob_start();
$r = htmlspecialchars((string) ($range ?? '30d'), ENT_QUOTES, 'UTF-8');
?>
<h1 class="h4 mb-2">Rolling report · <?= $r ?></h1>
<p class="text-secondary small mb-4">Grain reflects partner bank posting windows · warehouse mirror on <code>staging.payflow.local</code> still shows shadow cohorts.</p>
<div class="row g-3">
    <div class="col-md-4"><div class="border rounded p-3 bg-white shadow-sm"><div class="text-muted small">Disbursements initiated</div><div class="fs-4 fw-semibold">418</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 bg-white shadow-sm"><div class="text-muted small">Median posting latency</div><div class="fs-4 fw-semibold">11m</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 bg-white shadow-sm"><div class="text-muted small">Open exceptions</div><div class="fs-4 fw-semibold">6</div></div></div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
