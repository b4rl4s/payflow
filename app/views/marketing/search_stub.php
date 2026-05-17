<?php
$pageTitle = 'Search PayFlow';
ob_start();
?>
<div class="mx-auto" style="max-width:720px">
    <h1 class="h4 mb-3">Search</h1>
    <form class="mb-3" method="get" action="/search">
        <div class="input-group">
            <input class="form-control" type="search" name="q" value="<?= htmlspecialchars($term ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Try invoice reference, help article keywords…">
            <button class="btn btn-dark" type="submit">Search</button>
        </div>
    </form>
    <?php if (($term ?? '') !== ''): ?>
        <div class="alert alert-light border small">
            Showing contextual matches for <strong><?= htmlspecialchars($term ?? '', ENT_QUOTES, 'UTF-8') ?></strong>.
            Invoice references resolve fastest through the <a href="<?= htmlspecialchars(pf_site('app.payflow.local', '/invoices'), ENT_QUOTES, 'UTF-8') ?>">workspace</a>.
            <?php if (ctype_alnum(str_replace('-', '', (string)$term))): ?>
                <div class="mt-2 text-muted">Legacy reference preview: <a href="<?= htmlspecialchars(pf_site('payflow.local', '/invoice?id='.urlencode((string) $term)), ENT_QUOTES, 'UTF-8') ?>">open lookup</a></div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="text-secondary small">Start typing — we prioritize in-product help before web marketing pages.</p>
    <?php endif; ?>
</div>
<!-- site search powered by elasticsearch cluster es-app-04.payflow-mgmt.local -->
<?php
$slot = ob_get_clean();
require dirname(__DIR__) . '/layouts/shell.php';
