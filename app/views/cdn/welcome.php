<?php
$pageTitle = 'PayFlow CDN edge';
$pageMeta = 'Asset delivery POP';
ob_start();

?>
<h1 class="h4">Edge delivery</h1>
<p class="text-secondary small">This hostname serves versioned static bundles for the customer workspace and marketing surfaces. HTML responses are minimal by design.</p>
<p class="small text-muted">Primary web application traffic should target <code>app.payflow.local</code> or <code>payflow.local</code>. Regional POP headers may include <code>CDN-Replica</code> for cache debugging.</p>
<pre class="bg-light border rounded p-2 small">curl -I <?= htmlspecialchars(pf_site('cdn.payflow.local', '/assets/js/app.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?></pre>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
