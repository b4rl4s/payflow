# PayFlow phased migration — finance core

Audience: integrations + treasury partners only.

## Hosts referenced in rollout scripts

- `staging.payflow.local` preview cluster (SOC evidence separate from prod ledgers).
- `dev-api.payflow.local` sand-boxed payloads for ACH simulation.
- `beta.payflow.local` browser canary cohort (may serve newer asset hashes).
- `cdn.payflow.local` authoritative static bundle fingerprints.

## Service paths (non-navigational)

Archived engineering aliases still appear in Grafana annotations:

`/api/private/overview`, `/api/debug/users`, `/api/internal/reports`, `/legacy/billing/passive`.

**Action:** Confirm DNS split-horizon for `mirror.internal.payflow.net` before customer traffic cutovers.
