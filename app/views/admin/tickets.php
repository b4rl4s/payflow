<?php
$pageTitle = 'Escalations queue';
ob_start();

?>
<h1 class="h4 mb-3">Escalations queue</h1>
<div class="list-group shadow-sm">
    <div class="list-group-item"><span class="badge text-bg-secondary me-2">P2</span> NetSuite ingestion backpressure — integrations team</div>
    <div class="list-group-item"><span class="badge text-bg-warning text-dark me-2">P1</span> ACH return spike region US-EAST — partner bank bridge</div>
    <div class="list-group-item"><span class="badge text-bg-light border me-2">P3</span> Changelog typo on EU residency note — marketing</div>
</div>
<p class="small text-muted mt-3 mb-0">Pager bridge still references legacy host <code>dev-api.payflow.local</code> from May rehearsal — confirm DNS before failover drill.</p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
