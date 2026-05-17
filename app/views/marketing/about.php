<?php
$pageTitle = 'About PayFlow';
ob_start();
?>
<h1 class="h3 mb-3">We build calm infrastructure for frantic finance calendars.</h1>
<p class="text-secondary">PayFlow spun out from a treasury automation group inside a multinational logistics operator. Today we operate across New York and Dublin with daylight coverage in Auckland for APAC disbursements.</p>
<div class="row g-4 py-3">
    <div class="col-md-6">
        <h2 class="h5">Operating principles</h2>
        <ul class="text-secondary small">
            <li>Human-readable ledgers beat black-box scoring.</li>
            <li>Uptime empathy: every minute of delay is someone's close checklist slipping.</li>
            <li>Security investment is a product feature, not a tax.</li>
        </ul>
    </div>
    <div class="col-md-6">
        <h2 class="h5">Leadership snapshot</h2>
        <ul class="list-unstyled small text-secondary">
            <li><strong class="text-body">Elena Marquez</strong> — CEO (ex-Goldman treasury tech)</li>
            <li><strong class="text-body">Noah Patel</strong> — CPTO (ex-Stripe platform risk)</li>
            <li><strong class="text-body">Riley Chen</strong> — CFO (ex-Square corporate development)</li>
        </ul>
    </div>
</div>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
