<nav class="navbar navbar-expand-lg navbar-light bg-warning-subtle border-bottom mb-3">
    <div class="container-xl">
        <span class="navbar-brand text-dark">Operations console</span>
        <span class="navbar-text small"><code><?= htmlspecialchars(($ctx ?? [])['host'] ?? '', ENT_QUOTES, 'UTF-8') ?></code></span>
        <div class="ms-auto small">
            <a class="me-3" href="/support/tickets">Escalations</a>
            <a class="me-3" href="/audit">Audit trail</a>
            <a class="text-muted" href="<?= htmlspecialchars(pf_site('payflow.local', '/changelog'), ENT_QUOTES, 'UTF-8') ?>">Changelog mirror</a>
        </div>
    </div>
</nav>
