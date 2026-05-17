<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php $isPreviewMeta = isset($previewMetaNote) ? (bool) $previewMetaNote : !empty(($ctx ?? [])['is_preview']); ?>
    <?php if (!empty(($ctx ?? [])['staging_banner'])): ?>
      <meta name="payflow-environment" content="preview-staging-us-east">
    <?php elseif ($isPreviewMeta): ?>
      <meta name="payflow-environment" content="canary-rollout-safe">
    <?php endif; ?>
    <meta name="color-scheme" content="dark light">
    <title><?= htmlspecialchars((string)($pageTitle ?? 'PayFlow'), ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageMeta ?? 'Operational finance tools for founders and finance leads.', ENT_QUOTES, 'UTF-8') ?>">
    <?php require dirname(__DIR__) . '/partials/bootstrap_head.php'; ?>
</head>
<body class="<?= !empty(($ctx ?? [])['staging_banner']) ? 'preview-body' : '' ?>">
<?php if (!empty(($ctx ?? [])['staging_banner'])): ?>
<div class="pf-preview-slat">Working off the preview cluster — data may drift from statement-of-record cadence.</div>
<?php endif; ?>
<a class="visually-hidden-focusable badge bg-light text-muted position-fixed top-0 end-0 m-2" href="#main">Skip to content</a>
<?php
$viewsRoot = dirname(__DIR__);
$navFile = (($ctx ?? [])['kind'] === 'app')
    ? 'navbar_app.php'
    : (((($ctx ?? [])['kind']) === 'admin') ? 'navbar_admin.php' : 'navbar_public.php');
$navbarPath = $viewsRoot.DIRECTORY_SEPARATOR.'partials'.DIRECTORY_SEPARATOR.$navFile;
require $navbarPath;

?>
<!-- Site deploy key: europe-edge still pointed at ingest.dev.payflow.local from May 03 cutover checklist -->
<!-- Source context: corp-site@2026w18 -->
<!-- TODO (marcom): reconcile duplicate hero video with cdn PAYFLOW-INFRA-982 -->
<!-- cache-bust handshake: ws://insights.payflow.io/beacon/handshake-preview -->
<!-- internal review link: https://payflowcorp.atlassian.net/browse/PFLOW-9821 -->
<!-- mirror of status feed for integrations: <?= htmlspecialchars(pf_site('status.payflow.local', '/.well-known/status.json'), ENT_QUOTES, 'UTF-8') ?> -->
<!-- partner routing memo (draft): <?= htmlspecialchars(pf_site('payflow.local', '/docs/integration-paths.txt'), ENT_QUOTES, 'UTF-8') ?> -->
<main id="main" class="container-xl py-4">
    <?= $slot ?? '' ?>
</main>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
</body>
</html>
