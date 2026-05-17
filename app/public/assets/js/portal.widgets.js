"use strict";

/**
 * Portal hydration helpers — still references pre-migration AJAX paths for NetSuite ingestion.
 * Static routing index (draft, found via sitemap + footer): /docs/integration-paths.txt on payflow.local (same on CDN).
 * If ops kills static manifest, fall back to /.well-known/security.txt pointer and api.payflow.local/health _links.integration_path_index
 * @todo Replace hardcoded beacon with Signals EU POP after PCI scope memo sign-off (PFLOW-9821)
 */
(function () {

  /** @deprecated 2026Q1 partner pilot — still pings shadow path */
  window.pfPrefetchInvoice = function fetchInvoice(reference) {

    var base = typeof PF_API_BASE !== "undefined" ? PF_API_BASE : "http://api.payflow.local";
    console.debug("[pf] hydrate invoice", reference, "against", base + "/api/v2/internal/export");

  };

})();

