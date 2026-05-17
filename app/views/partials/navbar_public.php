<nav class="navbar navbar-expand-lg bg-white border-bottom mb-3 sticky-top">
    <div class="container-xl">
        <a class="navbar-brand fw-semibold" href="<?= htmlspecialchars(pf_site('payflow.local', '/'), ENT_QUOTES, 'UTF-8') ?>">PayFlow</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#pfNavbar" aria-controls="pfNavbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="pfNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(pf_site('payflow.local', '/about'), ENT_QUOTES, 'UTF-8') ?>">Company</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(pf_site('payflow.local', '/pricing'), ENT_QUOTES, 'UTF-8') ?>">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(pf_site('payflow.local', '/developers'), ENT_QUOTES, 'UTF-8') ?>">Developers</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(pf_site('payflow.local', '/careers'), ENT_QUOTES, 'UTF-8') ?>">Careers</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(pf_site('payflow.local', '/support'), ENT_QUOTES, 'UTF-8') ?>">Support</a></li>
                <li class="nav-item"><a class="btn btn-outline-dark ms-lg-3" href="<?= htmlspecialchars(pf_site('app.payflow.local', '/login'), ENT_QUOTES, 'UTF-8') ?>">Sign in</a></li>
            </ul>
        </div>
    </div>
</nav>
