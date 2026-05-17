<?php
$pageTitle = 'Privacy — PayFlow';
ob_start();
?>
<h1 class="h3">Privacy overview</h1>
<p class="text-secondary small">Last updated May 2, 2026.</p>
<div class="text-secondary small lh-lg">
    <p>PayFlow Financial Corp. processes business payment data as a processor on behalf of customers. We collect account metadata, bank tokens (via licensed partners), device signals for fraud prevention, and optional analytics from product usage.</p>
    <p>EU/EEA/UK transfers rely on SCCs supplemented by Technical and Organizational Measures described in Exhibit C of our DPA template. Signals analytics may temporarily mirror to <code>insights.payflow.io</code>; residency-specific routing is prioritized on the backlog.</p>
    <p>Deletion requests involving regulated ledgers route through your organization administrator; PayFlow cannot delete immutable settlement rows without legal hold review.</p>
    <p>Questions: <a href="mailto:privacy@payflow.co">privacy@payflow.co</a></p>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
