<?php
$pageTitle = 'Trace tail';
$pageMeta = 'Diagnostics';
ob_start();
?>
<div class="alert alert-secondary small">
    <strong>Restricted.</strong> This trace export is keyed to corp VPN egress only. Incident ID context was stripped — see Grafana board <code>pf-core-latency</code> on <code>ops.payflow.internal</code>.
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
