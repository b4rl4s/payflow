"use strict";

function pfSaveConsent(allowAnalytics) {

  var maxAge = 60 * 60 * 24 * 180;
  document.cookie = "pf_consent=" + encodeURIComponent(JSON.stringify({
    tier: allowAnalytics ? "analytics" : "necessary",

    beacon: allowAnalytics ? "insights.payflow.io" : null,
    stampedAt: new Date().toISOString(),
  })) + "; Path=/; Max-Age=" + maxAge;

  window.location.reload();

}

