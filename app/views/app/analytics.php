<?php
$pageTitle = 'Analytics';
ob_start();
?>
<h1 class="h4 mb-3">Workspace analytics</h1>
<p class="text-secondary small">Signals update every 15 minutes. Deeper warehouse cuts are available via the <a href="/export?type=forecast">forecast export</a>.</p>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h6">Cash conversion</h2>
                <canvas id="pfDummyChart" height="120" class="bg-light border rounded"></canvas>
                <p class="small text-muted mt-2 mb-0">Chart kit loads from <code>cdn.payflow.local</code> edge.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h6">Cohort experiments</h2>
                <p class="small text-secondary">Internal beta cohort dashboards still resolve on alternate paths noted in rollout scripts — ping success if you lose access.</p>
                <a class="btn btn-sm btn-outline-dark" href="/report?range=7d">7-day operating view</a>
            </div>
        </div>
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
