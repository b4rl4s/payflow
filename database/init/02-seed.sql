SET NAMES utf8mb4;

INSERT INTO organizations (name, slug, plan_tier, country_code)
VALUES ('Northbeam Roasters Cooperative', 'northbeam-rc', 'enterprise', 'US');

SET @org := LAST_INSERT_ID();

INSERT INTO users (org_id, email, full_name, password_hash, role)
VALUES (
  @org,
  'admin@admin.com',
  'Demo workspace admin',
  '$2y$10$HCwiZ88yYa.7Xzkrx.hz2OvLg6nS04QPTsEXQGtahubbKlMUa9yPq',
  'owner'
);

INSERT IGNORE INTO invoices (org_id, issuer_user_id, customer_name, reference_code, currency, amount_cents, tax_cents, status, issued_on, metadata_json)
VALUES
  (@org, 1, 'Fulton Street Baking Co.', 'INV-2026-0392', 'USD', 12840000, 0, 'paid', '2026-04-03', '{"project":"kitchen-expansion"}'),
  (@org, 1, 'Harbor Freight & Supply', 'INV-2026-0419', 'USD', 48250000, 0, 'sent', '2026-04-29', '{"po":"HFS-MAR-9821"}'),
  (@org, 1, 'Cedarline Analytics', 'INV-2026-0448', 'USD', 2160000, 0, 'paid', '2026-05-02', NULL);

INSERT IGNORE INTO invoices (org_id, issuer_user_id, customer_name, reference_code, currency, amount_cents, tax_cents, status, issued_on, archived)
VALUES (@org, 1, 'Violet & Co Interiors', 'INV-2025-9911', 'USD', 96000000, 0, 'paid', '2025-11-04', 1);

INSERT INTO transactions (org_id, invoice_id, provider_ref, amount_cents, fee_cents, method, status, settled_on)
SELECT @org, id, 'PF-TRX-928177', 12840000, 38400, 'ach', 'posted', '2026-04-05' FROM invoices WHERE reference_code = 'INV-2026-0392' LIMIT 1;
INSERT INTO transactions (org_id, invoice_id, provider_ref, amount_cents, fee_cents, method, status, settled_on)
VALUES (@org, NULL, 'PF-TRX-928201', 50000000, 150000, 'wire', 'posted', '2026-04-11');

INSERT INTO api_clients (org_id, label, masked_key_suffix, webhook_url, revoked)
VALUES (@org, 'NetSuite ingestion', 'sk_live_····A7k9', NULL, 0),
       (@org, 'Ops automation (legacy)', 'sk_live_····Q2mz', NULL, 1);

INSERT INTO audit_notes (actor_email, body) VALUES
  ('infra@northbeamcoffee.co','Cutover rehearsal completed on sandbox; mirror host dev-api.payflow.local still reachable from corp VPN.'),
  ('platform@northbeamcoffee.co','Reminder: archival exports should flow through billing exports service, not the manual /internal/export window after June.');
