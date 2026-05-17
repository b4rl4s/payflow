<?php
$pageTitle = 'Trust & security — PayFlow';
ob_start();
?>
<h1 class="h3 mb-3">Trust at PayFlow</h1>
<p class="text-secondary small">We publish high-level attestations here. Detailed evidence requests go through your customer success director under NDA.</p>
<ul class="small text-secondary">
    <li>SOC 2 Type II audit window begins Q3 2026 with Prescient Assurance.</li>
    <li>Bug bounty coordination through <a href="mailto:security@payflow.co">security@payflow.co</a> — scope includes <code>*.payflow.local</code> customer preview stacks when explicitly listed in your invite.</li>
    <li>Responsible disclosure SLA: severity-based, P1 acknowledgement within 24h business days.</li>
    <li>MDM for corporate devices; customer admin sessions can be constrained by IP allow lists (Enterprise).</li>
</ul>
<p class="text-muted small mt-3 mb-0">Public <code>security.txt</code> references the same routing memo — <a href="<?= htmlspecialchars(pf_site('payflow.local', '/.well-known/security.txt'), ENT_QUOTES, 'UTF-8') ?>">/.well-known/security.txt</a></p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
