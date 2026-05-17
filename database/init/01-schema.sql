SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS organizations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  plan_tier ENUM('starter','growth','enterprise') NOT NULL DEFAULT 'growth',
  country_code CHAR(2) NOT NULL DEFAULT 'US',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  org_id BIGINT UNSIGNED NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  full_name VARCHAR(160) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('owner','finance','operations','readonly') NOT NULL DEFAULT 'finance',
  last_login_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organizations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS invoices (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  org_id BIGINT UNSIGNED NOT NULL,
  issuer_user_id BIGINT UNSIGNED DEFAULT NULL,
  customer_name VARCHAR(200) NOT NULL,
  reference_code VARCHAR(64) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  amount_cents INT NOT NULL,
  tax_cents INT NOT NULL DEFAULT 0,
  status ENUM('draft','sent','paid','void') NOT NULL DEFAULT 'sent',
  issued_on DATE NOT NULL,
  metadata_json JSON DEFAULT NULL,
  archived TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organizations(id),
  UNIQUE KEY ux_reference (reference_code),
  INDEX idx_org_status (org_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transactions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  org_id BIGINT UNSIGNED NOT NULL,
  invoice_id BIGINT UNSIGNED DEFAULT NULL,
  provider_ref VARCHAR(64) DEFAULT NULL,
  amount_cents INT NOT NULL,
  fee_cents INT NOT NULL DEFAULT 0,
  method ENUM('ach','wire','card','instant') NOT NULL DEFAULT 'card',
  status ENUM('pending','posted','failed') NOT NULL DEFAULT 'posted',
  settled_on DATE DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organizations(id),
  FOREIGN KEY (invoice_id) REFERENCES invoices(id),
  UNIQUE KEY ux_provider_ref (provider_ref),
  INDEX idx_org_created (org_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS api_clients (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  org_id BIGINT UNSIGNED NOT NULL,
  label VARCHAR(120) NOT NULL,
  masked_key_suffix VARCHAR(32) NOT NULL,
  webhook_url VARCHAR(500) DEFAULT NULL,
  revoked TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organizations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_notes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actor_email VARCHAR(255) DEFAULT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
