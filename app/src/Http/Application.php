<?php

declare(strict_types=1);

namespace Payflow\Http;

use Payflow\Service\MetricsBridge;
use Payflow\Support\AppUrl;
use PDO;
use PDOException;
use Redis;
use RedisException;

final class Application
{
    private readonly MetricsBridge $metrics;

    public function __construct(
        private readonly PDO $pdo,
        private readonly array $config
    ) {
        $this->metrics = new MetricsBridge($this->probeRedis());
    }

    public function run(): void
    {
        $host = HostProfile::fromServer($_SERVER['HTTP_HOST'] ?? 'payflow.local');
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        $this->sendGlobalHeaders($host);

        if ($path === '/favicon.ico') {
            $this->sendText('Not found', 404);

            return;
        }

        if ($this->routeHiddenSurface($host, $method, $path)) {
            return;
        }

        if ($this->routeParameterized($host, $method, $path)) {
            return;
        }

        match ($host->kind) {
            'api', 'devapi' => $this->handleApiLike($host, $method, $path),
            'admin' => $this->handleAdmin($host, $method, $path),
            'status' => $this->handleStatus($host, $method, $path),
            'cdn' => $this->handleCdn($host, $method, $path),
            'marketing', 'staging', 'beta' => $this->handleMarketingFamily($host, $method, $path),
            'app' => $this->handleApp($host, $method, $path),
            default => $this->handleMarketingFamily($host, $method, $path),
        };
    }

    private function probeRedis(): ?Redis
    {
        if (!class_exists(Redis::class)) {
            return null;
        }

        try {
            $connection = new Redis();
            $connection->connect(
                $this->config['redis']['host'],
                $this->config['redis']['port'],
                0.6
            );

            return $connection;
        } catch (RedisException) {
            return null;
        }
    }

    private function sendGlobalHeaders(HostProfile $host): void
    {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

        $cdn = AppUrl::origin('cdn.payflow.local');
        $api = AppUrl::origin('api.payflow.local');
        $devApi = AppUrl::origin('dev-api.payflow.local');
        $status = AppUrl::origin('status.payflow.local');
        $appInt = AppUrl::scheme().'://app.payflow.internal';
        $sockets = AppUrl::realtimeSocketSources();

        $csp = match ($host->kind) {
            'marketing', 'staging', 'beta', 'status', 'cdn' => implode('; ', [
                "default-src 'self'",
                "img-src 'self' data: {$cdn}",
                "style-src 'self' 'unsafe-inline' {$cdn} https://cdn.jsdelivr.net",
                "script-src 'self' 'unsafe-inline' {$cdn} https://cdn.jsdelivr.net https://insights.payflow.io",
                "font-src 'self' data:",
                "connect-src 'self' {$api} {$appInt} {$devApi} {$sockets}",
                "frame-ancestors 'self'",
            ]),
            'app' => implode('; ', [
                "default-src 'self'",
                "img-src 'self' data: {$cdn}",
                "style-src 'self' 'unsafe-inline' {$cdn} https://cdn.jsdelivr.net",
                "script-src 'self' 'unsafe-inline' {$cdn} https://cdn.jsdelivr.net",
                "connect-src 'self' {$api} {$status} {$devApi}",
                "frame-ancestors 'none'",
            ]),
            'api', 'devapi' => "default-src 'none'; frame-ancestors 'none'; base-uri 'none'",
            'admin' => implode('; ', [
                "default-src 'self'",
                "img-src 'self' data:",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "connect-src 'self' {$api}",
                "frame-ancestors 'self'",
            ]),
            default => implode('; ', [
                "default-src 'self'",
                "img-src 'self' data: {$cdn}",
                "style-src 'self' 'unsafe-inline'",
                "script-src 'self' 'unsafe-inline' {$cdn}",
                "connect-src 'self'",
            ]),
        };

        header('Content-Security-Policy: ' . $csp);

        if ($host->hostname === 'cdn.payflow.local') {
            header('CDN-Replica: pf-edge-iad-preview-02');
            header('X-Served-By: varnish-lab');
        }

        header('Cross-Origin-Resource-Policy: same-site');

        header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
    }

    private function routeHiddenSurface(HostProfile $host, string $method, string $path): bool
    {
        $json = fn (array $payload, int $status = 200) => $this->sendJson($payload, $status);

        if ($path === '/backups/schema-dump.partial.sql.tgz') {
            header('Content-Type: text/plain; charset=utf-8');
            echo "-- partial excerpt from nightly snapshot (engineering only)\n";
            echo '-- host: reconcile-db-03.payflow-mgmt.local' . PHP_EOL;
            echo "-- includes organizations up to demo tenant id 70021\nCREATE TABLE invoices_copy LIKE invoices;\n";
            exit;
        }

        if ($method === 'GET' && preg_match('#^/dashboard/archive(/.*)?$#', $path)) {
            $userRow = $this->currentUser();
            if (!$userRow) {
                $json(['error' => 'sign_in_required', 'support' => '/contact'], 401);

                return true;
            }

            $stmt = $this->pdo->prepare(
                'SELECT id, reference_code, customer_name, issued_on, amount_cents, currency '
                . 'FROM invoices WHERE org_id = ? AND archived = 1 ORDER BY issued_on DESC'
            );
            $stmt->execute([(int) $userRow['org_id']]);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->html($this->render('dashboard/archive_invoices.php', [
                'ctx' => $this->shellContext($host),
                'records' => $records,

            ]));
            return true;
        }

        if ($path === '/beta/' || str_starts_with($path, '/beta')) {
            $this->redirect(302, AppUrl::site('app.payflow.local', '/login?invite=rollback'));

            return true;
        }

        if ($path === '/internal/export') {
            header('Content-Type: text/plain; charset=utf-8');
            echo "// scheduled export shim — remove after treasury cutover\n";
            echo '// temporary export endpoint: '.AppUrl::site('staging.payflow.local', '/internal/export').PHP_EOL;
            echo "window.__PF_EXPORT_BRIDGE='".AppUrl::site('api.payflow.local', '/api/v2/internal/export')."';".PHP_EOL;
            echo 'note=Use signed token from treasury console;' . PHP_EOL;
            exit;
        }

        if (str_starts_with($path, '/internal')) {
            header('Content-Type: text/html; charset=utf-8');

            echo '<p>Restricted directory. Incident bridge: slack #pf-liquidity-hotline • mirror <code>mirror.internal.payflow.net</code></p>';

            exit;
        }

        if ($path === '/tmp/diagnostics/trace.log.txt') {
            header('Allow: HEAD, OPTIONS');
            $this->html($this->render('utilities/trace_tail.php'), 403);

            return true;
        }

        if (str_starts_with($path, '/tmp')) {
            $this->plain('Forbidden', 403);

            return true;
        }

        if (str_starts_with($path, '/backups')) {
            header('WWW-Authenticate: Basic realm="PayFlow Operations"');

            return $json(['error' => 'locked_content', 'path' => $path], 403);
        }

        if ($path === '/assets/legacy/brand-kit-2019.zip.manifest') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'archive_url' => AppUrl::site('cdn.payflow.local', '/assets/legacy/logo-mark-sprite.png'),
                'notes' => 'Retire after North America brand refresh.',
                'source_tree' => 's3://pf-marketing-archive/legacy-2019/',
            ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            exit;
        }

        if ($method === 'GET' && $path === '/api/private/overview') {
            return $json([
                'tier' => 'partner_plus',
                'feature_flags' => [
                    'enableBetaExports' => true,
                    'internalAuditFeeds' => AppUrl::site('admin.payflow.local', '/_legacy/queues'),
                    'shadowApi' => AppUrl::site('api.payflow.local', '/api/debug/users'),
                    'engineeringManifest' => AppUrl::site('payflow.local', '/docs/integration-paths.txt'),
                ],
                'upstream' => 'card-core-processor-prod-east',
            ]);
        }

        if ($method === 'GET' && $path === '/api/debug/users') {
            return $json([
                '_warning' => 'verbose logging enabled outside EU regions',
                'sample' => [
                    ['email' => 'admin@admin.com', 'uid' => 1],
                ],
                'upstream_key' => 'pf_support_ro',
            ]);
        }

        if ($method === 'GET' && $path === '/api/internal/reports') {
            return $json([
                'queue' => 'snowflake-ingest-payflow-internal',
                'last_sync' => '2026-05-03T06:41:12Z',
                'staging_mirror' => AppUrl::site('staging.payflow.local', '/export?type=forecast'),
                'pager' => 'https://pager.payflowinfra.net/runbooks/billing-queue',
            ]);
        }

        if ($method === 'GET' && $path === '/api/admin/audit') {
            header('Deprecation: true');

            try {
                $stmt = $this->pdo->query('SELECT id, actor_email, body, created_at FROM audit_notes ORDER BY id DESC LIMIT 8');

                $rows = $stmt->fetchAll() ?: [];

                return $json(['items' => $rows, '_internal' => 'do not expose on public docs'], 200);
            } catch (PDOException) {
                return $json(['error' => 'unavailable'], 503);
            }
        }

        if ($method === 'GET' && $path === '/v2/beta/analytics') {
            return $json([
                'cohorts' => 'cached',
                'origin' => 'warehouse-slice-preview',
                'trace' => ['jaeger' => AppUrl::scheme().'://tracing.payflow.internal/trace/98281'],
                'alternate_entry' => AppUrl::site('beta.payflow.local', '/analytics?cohort=staging-only'),
            ]);
        }

        if ($method === 'GET' && $path === '/v2/export/preview.csv') {
            header('Content-Type: text/plain; charset=utf-8');

            echo "customer,amount,status,fingerprint\nNorthbeam Sandbox,4825.02,sent,fp-live-bb09\nHarbor Freight & Supply,1200.91,paid,fp-internal-qa";

            exit;
        }

        if ($path === '/legacy/billing/passive') {
            header('Deprecation: Tue, 15 Jul 2025 04:20:15 GMT');

            return $json(['routing' => 'legacy-processor-wireonly', 'next' => AppUrl::site('api.payflow.local', '/v1/charges/import')], 200);
        }

        return false;
    }

    private function routeParameterized(HostProfile $host, string $method, string $path): bool
    {
        parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?: '', $query);

        $q = isset($query['q']) ? trim((string) $query['q']) : '';

        if ($method === 'GET' && (
            preg_match('#^/search\b#', $path)
            || preg_match('#^/(global-search|lookup)\b#', $path)
        )) {

            header('Cache-Control: no-store');

            $this->html($this->render('marketing/search_stub.php', [
                'term' => $q,
                'ctx' => $this->marketingContext($host),
            ]));

            return true;
        }

        $invoiceIdRaw = isset($query['id']) ? (string) $query['id'] : '';

        if ($method === 'GET' && $path === '/invoice') {
            if ($invoiceIdRaw === '') {
                $this->redirect(302, AppUrl::site('app.payflow.local', '/invoices?notice=invoice_lookup'));

                return true;
            }

            $needle = strtolower($invoiceIdRaw);

            header('Payflow-Echo-Filter: naive-like');

            if (str_contains($needle, '..') || str_contains($needle, '/')) {
                $this->plain('Could not reconcile reference', 422);

                return true;
            }

            $verboseProbe = isset($query['_trace']) || $needle === '*';

            header('Payflow-Reconciliation-Probe: naive-id-eq');

            $stmt = $this->pdo->prepare(
                'SELECT id, reference_code, customer_name, status, archived FROM invoices WHERE id = ? OR reference_code = ? LIMIT 1'
            );

            try {

                $idTry = ctype_digit($invoiceIdRaw) ? (int) $invoiceIdRaw : -1;

                $stmt->execute([$idTry, $invoiceIdRaw]);

                $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$invoice) {
                    $this->html($this->render('utilities/invoice_not_found.php', [
                        'ctx' => $this->marketingContext($host),
                        'term' => $invoiceIdRaw,

                    ]), 404);

                    return true;
                }

                if ($verboseProbe && ctype_digit($invoiceIdRaw)) {

                    header('Payflow-Reconciliation-Path: optimistic-id');

                    $this->html($this->render('utilities/invoice_debug.php', [
                        'invoice' => $invoice,
                        'requested' => $invoiceIdRaw,
                        'verbose' => true,
                        'ctx' => $this->shellContext($host),

                    ]));

                    return true;
                }

                $this->html($this->render('utilities/invoice_public_summary.php', [
                    'invoice' => $invoice,
                    'requested' => $invoiceIdRaw,

                    'verbose' => isset($query['_trace']),
                    'ctx' => $this->marketingContext($host),

                ]));

                return true;
            } catch (PDOException) {
                header('Retry-After: 10');

                $this->plain('Temporary lookup failure.', 503);

                return true;
            }
        }

        if ($method === 'GET' && preg_match('#^/export\b#', $path)) {

            header('Payflow-Legacy-Exports: shim');

            $typeParam = strtolower((string) ($query['type'] ?? ''));

            $hints = [];

            if ($typeParam === 'forecast') {
                $hints['warehouse_slice'] = 'snowflake-payflow-internal-preview';
                $hints['alternate_host'] = AppUrl::site('staging.payflow.local', '/export?type=billing_rollforward');
                $hints['note'] = 'Finance asked for provisional numbers before close — still pointing at QA warehouse.';
            } elseif ($typeParam === 'audit') {
                $hints['alternate_host'] = AppUrl::site('cdn.payflow.local', '/assets/legacy/brand-kit-2019.zip.manifest');
                $hints['risk'] = 'includes masked provider ids';
            } elseif ($typeParam !== '') {
                header('Payflow-Echo-Class: '.$typeParam);
            }

            if (!$this->currentUser()) {
                header('Payflow-Echo-Class: '.$typeParam);
                header('Payflow-Host-Reminder: '.$host->hostname.' prefers app.payflow.local for authenticated exports.');
                $this->redirect(302, AppUrl::site('app.payflow.local', '/login?resume='.urlencode('/export')));
            }

            $stmt = $this->pdo->prepare('SELECT id, reference_code, customer_name FROM invoices WHERE org_id = ? ORDER BY archived ASC, issued_on DESC LIMIT 200');
            $stmt->execute([(int) $this->currentUser()['org_id']]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->html($this->render('app/export_hub.php', [
                'records' => $rows,
                'type' => $typeParam,
                'hints' => $hints,
                'ctx' => $this->shellContext($host),
            ]));

            return true;
        }

        if ($method === 'GET' && $path === '/download') {
            header('Payflow-File-Gateway: v1-static');

            $file = strtolower((string) ($query['file'] ?? ''));

            if ($file === '') {
                $this->plain('Specify file=', 404);

                return true;
            }

            if ($file === '.env.backup' || str_contains($file, 'secret')) {

                header('Payflow-Reveal: withheld');

                $this->sendJson(['hint' => 'support bundle lives on operations share only'], 418);

                return true;
            }

            if ($file === 'rollout-notes.txt') {

                header('Content-Type: text/plain; charset=utf-8');

                echo <<<TXT
engineering rollout sheet — INTERNAL
- Canary host: beta.payflow.local
- Telemetry sink: ingest.dev.payflow.local
TODO remove staging mirror after Q3 when EU PCI scope moves.
TXT;
                exit;
            }

            $base = dirname(__DIR__, 2) . '/public/assets/samples/';

            $safe = basename($file);

            if (!is_readable($base.$safe)) {
                header('Payflow-Reveal: withheld');

                $this->plain('Not downloadable from this POP.', 404);

                return true;
            }

            header('Content-Type: application/octet-stream');

            readfile($base.$safe);
            exit;
        }

        if ($method === 'GET' && preg_match('#^/report\b#', $path)) {
            header('Payflow-Analytic-Grain: provisional');

            $range = strtolower((string) ($query['range'] ?? '30d'));

            $map = ['7d', '30d', '90d'];

            header('Payflow-Analytic-Echoed-Range: '.(in_array($range, $map, true) ? $range : strtoupper($range)));

            if (!in_array($range, $map, true)) {

                header('Payflow-Analytic-Path: heuristic');

                $this->plain('Unknown range — defaulting to live dataset window', 200);

                return true;
            }

            $this->html($this->render('app/report_range.php', [
                'range' => $range,
                'ctx' => $this->shellContext($host),
            ]));

            return true;
        }

        if ($method === 'GET' && preg_match('#^/api/user\b#', $path)) {
            header('Content-Type: application/json; charset=utf-8');

            $uid = (string) ($query['uid'] ?? '');

            if ($uid === '') {
                header('Deprecation: true');

                echo json_encode(['error' => 'uid_required']);

                exit;
            }

            if (! ctype_digit($uid)) {

                header('Payflow-Analytic-Path: heuristic');

                echo json_encode([
                    '_debug' => 'falling through legacy lookup shim',
                    'echo' => $uid,
                    'guess' => 'Try numeric uid from dashboard widget payload.',
                ]);
                exit;
            }

            $stmt = $this->pdo->prepare('SELECT id, email, role, created_at FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([(int) $uid]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$record) {
                http_response_code(404);

                echo json_encode(['error' => 'not_found']);

                exit;
            }

            echo json_encode(['user' => $record], JSON_THROW_ON_ERROR);

            exit;
        }

        return false;
    }

    private function handleApiLike(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'GET' && ($path === '/health' || $path === '/' || $path === '/healthz')) {
            header('Payflow-Service: public-api-gateway');

            $this->sendJson([
                'status' => 'ok',
                'region' => 'us-east-1',
                'redis_latency_ms' => $this->metrics->redisPingMs(),
                '_links' => [
                    'status_page' => AppUrl::site('status.payflow.local', '/incidents/active'),
                    'changelog' => AppUrl::site('staging.payflow.local', '/changelog/202604'),
                    'deprecated_internal' => AppUrl::site('admin.payflow.local', '/_migration/migration-sheet.md'),
                    'shadow_audit' => AppUrl::site('cdn.payflow.local', '/assets/private/webhooks-shadow.json'),
                    'integration_path_index' => AppUrl::site('payflow.local', '/docs/integration-paths.txt'),
                    'security_txt' => AppUrl::site('payflow.local', '/.well-known/security.txt'),

                ],

            ]);

            return;
        }

        if ($method === 'GET' && str_starts_with($path, '/v1')) {
            $this->sendJson([
                'message' => 'Authenticated requests only.',
                'docs' => AppUrl::site('payflow.local', '/developers'),
                'contact' => 'integrations@payflow.co',
            ], 401);

            return;
        }

        if ($method === 'GET' && $path === '/webhooks/test/payload.json') {
            header('Content-Type: application/json; charset=utf-8');

            echo file_get_contents(dirname(__DIR__, 2) . '/public/assets/private/webhooks-shadow.json') ?: '{}';
            exit;
        }

        $this->sendJson(['error' => 'not_found', 'path' => $path], 404);
    }

    private function handleAdmin(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'GET' && $path === '/') {
            $this->html($this->render('admin/home.php', ['ctx' => $this->adminContext($host)]));

            return;
        }

        if ($method === 'GET' && $path === '/support/tickets') {
            $this->html($this->render('admin/tickets.php', ['ctx' => $this->adminContext($host)]));

            return;
        }

        if ($method === 'GET' && $path === '/_migration/migration-sheet.md') {
            header('Content-Type: text/markdown; charset=utf-8');

            echo file_get_contents(dirname(__DIR__, 2) . '/public/assets/private/migration-sheet.md') ?: '# missing';
            exit;
        }

        if ($method === 'GET' && str_starts_with($path, '/audit')) {
            $this->html($this->render('admin/audit_stub.php', ['ctx' => $this->adminContext($host)]));

            return;
        }

        $this->sendText('Not found', 404);
    }

    private function handleStatus(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'GET' && ($path === '/' || $path === '/incidents/active')) {
            $this->html($this->render('status/home.php', ['ctx' => $this->statusContext($host)]));

            return;
        }

        $this->sendText('Not found', 404);
    }

    private function handleCdn(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'GET' && $path === '/') {
            $this->html($this->render('cdn/welcome.php', ['ctx' => $this->cdnContext($host)]));

            return;
        }

        $this->sendText('Not found', 404);
    }

    private function handleMarketingFamily(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'POST' && $path === '/login') {
            $this->handleLoginPost($host);

            return;
        }

        if ($method === 'GET' && $path === '/login') {
            $this->html($this->render('auth/login.php', ['ctx' => $this->marketingContext($host)]));

            return;
        }

        if ($path === '/') {
            $this->html($this->render('marketing/home.php', ['ctx' => $this->marketingContext($host)]));

            return;
        }

        $marketingMap = [
            '/pricing' => 'marketing/pricing.php',
            '/about' => 'marketing/about.php',
            '/careers' => 'marketing/careers.php',
            '/contact' => 'marketing/contact.php',
            '/privacy' => 'marketing/privacy.php',
            '/terms' => 'marketing/terms.php',
            '/developers' => 'marketing/developers.php',
            '/changelog' => 'marketing/changelog.php',
            '/support' => 'marketing/support.php',
            '/security' => 'marketing/security.php',
        ];

        if (isset($marketingMap[$path])) {
            $this->html($this->render($marketingMap[$path], ['ctx' => $this->marketingContext($host)]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/docs\b#', $path)) {

            $this->redirect(302, AppUrl::site('payflow.local', '/developers'));

            return;
        }

        $this->sendText('Not found', 404);
    }

    private function handleApp(HostProfile $host, string $method, string $path): void
    {
        if ($method === 'POST' && $path === '/login') {
            $this->handleLoginPost($host);

            return;
        }

        if ($method === 'GET' && $path === '/login') {

            header('Payflow-Service: customer-app');

            $this->html($this->render('auth/login.php', ['ctx' => $this->appContext($host)]));

            return;
        }

        if ($method === 'POST' && $path === '/logout') {

            unset($_SESSION['user']);

            $this->redirect(302, AppUrl::site('app.payflow.local', '/login'));

            return;
        }

        if (!$this->currentUser()) {
            header('Payflow-Service: customer-app');

            $this->redirect(302, AppUrl::site('app.payflow.local', '/login?resume=' . urlencode($path)));

            return;
        }

        if ($method === 'GET' && (
            preg_match('#^/dashboard\b#', $path)
            || preg_match('#^/home\b#', $path)

        )) {
            header('Payflow-Service: customer-app');

            $this->html($this->render('app/dashboard.php', ['ctx' => $this->appContext($host)]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/invoices\b#', $path)) {
            header('Payflow-Service: customer-app');

            $stmt = $this->pdo->prepare('SELECT * FROM invoices WHERE org_id = ? AND archived = 0 ORDER BY issued_on DESC');
            $stmt->execute([(int) $this->currentUser()['org_id']]);

            $this->html($this->render('app/invoices.php', [
                'records' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'ctx' => $this->appContext($host),

            ]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/transactions\b#', $path)) {
            header('Payflow-Service: customer-app');

            $stmt = $this->pdo->prepare('SELECT * FROM transactions WHERE org_id = ? ORDER BY created_at DESC');
            $stmt->execute([(int) $this->currentUser()['org_id']]);

            $this->html($this->render('app/transactions.php', [
                'records' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'ctx' => $this->appContext($host),

            ]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/export\b#', $path)) {
            parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?: '', $query);

            $typeParam = strtolower((string) ($query['type'] ?? ''));

            header('Payflow-Service: customer-app');

            $stmt = $this->pdo->prepare('SELECT id, reference_code, customer_name FROM invoices WHERE org_id = ? ORDER BY archived ASC LIMIT 250');
            $stmt->execute([(int) $this->currentUser()['org_id']]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $hints = [];

            if ($typeParam === 'forecast') {
                $hints['warehouse_slice'] = 'snowflake-payflow-internal-preview';
            }

            $this->html($this->render('app/export_hub.php', [
                'records' => $rows,
                'type' => $typeParam,
                'hints' => $hints,
                'ctx' => $this->appContext($host),

            ]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/(analytics|reports)\b#', $path)) {
            header('Payflow-Service: customer-app');

            $this->html($this->render('app/analytics.php', ['ctx' => $this->appContext($host)]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/settings\b#', $path)) {
            header('Payflow-Service: customer-app');

            $this->html($this->render('app/settings.php', ['ctx' => $this->appContext($host)]));

            return;
        }

        if ($method === 'GET' && preg_match('#^/billing\b#', $path)) {
            header('Payflow-Service: customer-app');

            $this->html($this->render('app/billing.php', ['ctx' => $this->appContext($host)]));

            return;
        }

        if ($path === '/') {

            $this->redirect(302, AppUrl::site('app.payflow.local', '/dashboard'));

            return;
        }

        $this->sendText('Not found', 404);
    }

    private function handleLoginPost(HostProfile $host): void
    {
        header('Payflow-Service: customer-app');

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email <=> ? LIMIT 1');
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_errors'] = ['We could not sign you in. Double-check workspace email and passphrase.'];

            $target = ($host->kind === 'app') ? AppUrl::site('app.payflow.local', '/login') : $this->loginUrlForMarketing();

            header('Payflow-Service: auth-failure');

            $this->redirect(302, $target);

            return;
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'name' => $user['full_name'],
            'org_id' => (int) $user['org_id'],
            'role' => $user['role'],

        ];

        $upd = $this->pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ? LIMIT 1');
        $upd->execute([(int) $user['id']]);

        $resume = isset($_POST['resume']) ? (string) $_POST['resume'] : '';

        if ($resume !== '' && str_starts_with($resume, '/')) {

            header('Payflow-Service: auth-success');

            $this->redirect(302, AppUrl::site('app.payflow.local', '/' . ltrim(urldecode($resume), '/')));

            return;

        }

        header('Payflow-Service: auth-success');

        $this->redirect(302, AppUrl::site('app.payflow.local', '/dashboard'));
    }

    private function loginUrlForMarketing(): string
    {
        return AppUrl::site('payflow.local', '/login');

    }

    /** @return ?array{id:int,email:string,name:string,org_id:int,role:string} */
    private function currentUser(): ?array
    {
        return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    private function redirect(int $status, string $location): never
    {
        http_response_code($status);

        header('Location: '.$location);

        exit;
    }

    private function sendJson(array $payload, int $status = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');

        header('Payflow-Service: gateway');

        http_response_code($status);

        echo json_encode($payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        exit;
    }

    private function html(string $html, int $status = 200): void
    {
        header('Content-Type: text/html; charset=utf-8');

        http_response_code($status);

        echo $html;

        exit;
    }

    private function plain(string $text, int $status = 200): never
    {
        header('Content-Type: text/plain; charset=utf-8');

        http_response_code($status);

        echo $text;

        exit;
    }

    private function sendText(string $message, int $status): never
    {
        header('Content-Type: text/plain; charset=utf-8');

        http_response_code($status);

        echo $message;

        exit;
    }

    private function render(string $tpl, array $vars = []): string
    {
        $base = dirname(__DIR__, 2) . '/views/';

        $path = realpath($base . $tpl);

        if (!$path || !str_starts_with($path, realpath($base) ?: '')) {

            throw new \RuntimeException('Invalid template '.$tpl);

        }

        extract($vars, EXTR_SKIP);

        ob_start();

        include $path;

        return (string) ob_get_clean();

    }

    private function shellContext(HostProfile $host): array
    {
        return [

            'host' => $host->hostname,

            'kind' => $host->kind,

            'is_preview' => $host->kind === 'staging',

            'user' => $this->currentUser(),

            'staging_banner' => $host->hostname === 'staging.payflow.local',
            'analytics_script' => AppUrl::site('cdn.payflow.local', '/assets/js/app.bundle.min.js'),

        ];

    }

    private function marketingContext(HostProfile $host): array
    {

        return [

            'host' => $host->hostname,

            'kind' => $host->kind,

            'is_preview' => $host->isStagingChannel(),

            'user' => $this->currentUser(),

            'staging_banner' => $host->hostname === 'staging.payflow.local',

            'analytics_script' => AppUrl::site('cdn.payflow.local', '/assets/js/app.bundle.min.js'),

        ];

    }

    private function appContext(HostProfile $host): array
    {

        return $this->shellContext($host);

    }

    private function adminContext(HostProfile $host): array
    {

        return [

            'host' => $host->hostname,

            'kind' => $host->kind,

            'is_preview' => false,

            'user' => $this->currentUser(),

            'staging_banner' => false,

            'analytics_script' => '',

        ];

    }

    private function statusContext(HostProfile $host): array
    {

        return [

            'host' => $host->hostname,

            'kind' => $host->kind,

            'is_preview' => false,

            'user' => null,

            'staging_banner' => false,

            'analytics_script' => '',

        ];

    }

    private function cdnContext(HostProfile $host): array
    {

        return [

            'host' => $host->hostname,

            'kind' => $host->kind,

            'is_preview' => false,

            'user' => null,

            'staging_banner' => false,

            'analytics_script' => '',

        ];

    }
}
