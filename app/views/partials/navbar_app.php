<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-xl">
        <a class="navbar-brand" href="/dashboard">PayFlow Workspace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="appNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/invoices">Invoices</a></li>
                <li class="nav-item"><a class="nav-link" href="/transactions">Transactions</a></li>
                <li class="nav-item"><a class="nav-link" href="/analytics">Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="/export">Export center</a></li>
                <li class="nav-item"><a class="nav-link" href="/billing">Billing</a></li>
                <li class="nav-item"><a class="nav-link" href="/settings">Settings</a></li>
            </ul>
            <div class="d-flex gap-3 align-items-center text-white small">
                <div class="opacity-75 text-truncate" style="max-width:260px;">
                    <?= htmlspecialchars(($ctx ?? [])['user']['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>

                </div>
                <form method="post" action="/logout" class="mb-0">
                    <button class="btn btn-sm btn-outline-light" type="submit">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
