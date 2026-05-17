<?php
$pageTitle = 'Careers at PayFlow';
ob_start();
?>
<h1 class="h3 mb-3">Join the team shipping boring (in a good way) money software.</h1>
<p class="text-secondary">We hire across New York, remote US/Canada, and Dublin. PayFlow covers comprehensive health plans, ergonomic stipends, and actual vacation days Finance teams envy.</p>
<div class="table-responsive mt-4">
<table class="table table-sm align-middle">
    <thead><tr><th>Role</th><th>Location</th><th>Team</th></tr></thead>
    <tbody class="small">
        <tr><td>Senior Product Designer, Finance Workflows</td><td>New York hybrid</td><td>Design</td></tr>
        <tr><td>Payments Integration Engineer</td><td>Remote US</td><td>Partners</td></tr>
        <tr><td>Security Engineer (AppSec)</td><td>Dublin</td><td>Trust</td></tr>
        <tr><td>Customer Deployment Lead</td><td>Remote NA</td><td>Solutions</td></tr>
    </tbody>
</table>
</div>
<p class="small text-muted">Recruiting pipeline email: talent@payflow.co · Agencies: unsolicited profiles archived automatically.</p>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
