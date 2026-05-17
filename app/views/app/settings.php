<?php
$pageTitle = 'Account settings';
ob_start();
$u = ($ctx ?? [])['user'] ?? [];

?>
<h1 class="h4 mb-3">Workspace settings</h1>
<dl class="row small">
    <dt class="col-sm-3">Primary email</dt>
    <dd class="col-sm-9"><?= htmlspecialchars((string) ($u['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
    <dt class="col-sm-3">Role</dt>
    <dd class="col-sm-9"><?= htmlspecialchars((string) ($u['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
    <dt class="col-sm-3">Signals opt-in</dt>
    <dd class="col-sm-9">Enabled · EU residency routing <span class="text-muted">(preview)</span></dd>
</dl>
<div class="alert alert-secondary small mb-4">Webhook signing keys rotate independently — download new public keys from API settings once Finance approves vendor change windows.</div>
<form class="border rounded p-3 bg-light-subtle mb-4">
    <h2 class="h6">API identifiers</h2>
    <p class="small text-muted">Organization slug <code>northbeam-rc</code> is immutable. Need a sandbox key? Coordinate with integrations.</p>
    <button class="btn btn-sm btn-outline-secondary" type="button" disabled="">Rotate keys (SOC review)</button>
</form>
<form class="border rounded p-3" method="get" action="#" onsubmit="return false;">
    <h2 class="h6 text-muted mb-3">Debugging helpers (support only)</h2>
    <label class="form-label small">Lookup internal user identifiers</label>
    <div class="input-group input-group-sm mb-2" style="max-width:440px;">
        <span class="input-group-text"><code>?uid=</code></span>
        <input class="form-control font-monospace" type="text" placeholder="Numeric ID · see browser network tab payloads" aria-label="User id lookup demo">
        <button class="btn btn-outline-secondary" type="button" onclick="window.location='/api/user?uid=' + encodeURIComponent(this.previousElementSibling.value)">Open</button>
    </div>
    <p class="small text-muted mb-0">This shortcut hits the lightweight profile probe used by escalation engineers.</p>
</form>
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
