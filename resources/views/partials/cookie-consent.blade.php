{{-- Cookie consent banner — ISSUE 057 --}}
<div id="jm-cookie-consent" class="jm-cookie-consent" role="dialog" aria-live="polite" aria-label="Cookie consent" hidden>
    <div class="jm-cookie-consent__inner">
        <div class="jm-cookie-consent__text">
            <strong>We value your privacy</strong>
            <p>
                Essential cookies keep this site working (session, security, language).
                Optional cookies power embeds like YouTube and Google Maps.
                Choose what you allow — you can change this anytime via <button type="button" class="jm-link-btn" data-jm-cookie-settings>Cookie Settings</button> in the footer.
            </p>
            <div id="jm-cookie-consent-details" hidden>
                <h4>Cookie categories</h4>
                <ul>
                    <li><strong>Essential (always on):</strong> session, CSRF, language preference.</li>
                    <li><strong>Optional:</strong> YouTube player, Google Maps, other third-party embeds.</li>
                </ul>
            </div>
        </div>
        <div class="jm-cookie-consent__actions">
            <button type="button" class="btn btn-primary" data-jm-cookie-accept>Accept All</button>
            <button type="button" class="btn btn-outline-secondary" data-jm-cookie-reject>Reject Optional</button>
            <button type="button" class="btn btn-link" data-jm-cookie-customize aria-expanded="false" aria-controls="jm-cookie-consent-details">Customize</button>
        </div>
    </div>
</div>

<style>
.jm-cookie-consent {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    background: #1b1f2a;
    color: #f5f7fa;
    padding: 1rem 1.25rem;
    box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.35);
}
.jm-cookie-consent[hidden] { display: none !important; }
.jm-cookie-consent__inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-start;
    justify-content: space-between;
}
.jm-cookie-consent__text { flex: 1 1 320px; font-size: 0.95rem; }
.jm-cookie-consent__text p { margin: 0.4rem 0 0; line-height: 1.45; }
.jm-cookie-consent__actions { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
.jm-cookie-consent h4 { margin: 0.75rem 0 0.25rem; font-size: 0.95rem; }
.jm-cookie-consent ul { margin: 0; padding-left: 1.1rem; font-size: 0.875rem; }
.jm-link-btn {
    background: none;
    border: 0;
    color: #8ecae6;
    text-decoration: underline;
    cursor: pointer;
    padding: 0;
    font: inherit;
}
.jm-embed-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    min-height: 180px;
    background: #e9ecef;
    color: #495057;
    border: 1px dashed #adb5bd;
    border-radius: 4px;
    padding: 1rem;
    width: 100%;
}
.jm-embed-placeholder button { margin-top: 0.5rem; }
</style>

<script>
(function () {
    var STORAGE_KEY = 'jmor_cookie_consent';
    var banner = document.getElementById('jm-cookie-consent');
    if (!banner) { return; }

    var details = document.getElementById('jm-cookie-consent-details');
    var customizeBtn = banner.querySelector('[data-jm-cookie-customize]');

    function readConsent() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function writeConsent(value) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(value));
        } catch (e) { /* private mode — still apply for this page */ }
        applyConsent(value);
        hideBanner();
    }

    function hideBanner() {
        banner.hidden = true;
        if (details) { details.hidden = true; }
        if (customizeBtn) { customizeBtn.setAttribute('aria-expanded', 'false'); }
    }

    function showBanner() {
        banner.hidden = false;
    }

    function applyConsent(consent) {
        var allowOptional = !!(consent && consent.optional === true);

        document.querySelectorAll('[data-jm-optional-embed], [data-optional-embed]').forEach(function (el) {
            var src = el.getAttribute('data-src') || el.getAttribute('data-embed-src');
            if (!src) { return; }

            if (allowOptional) {
                if (el.tagName === 'IFRAME') {
                    if (!el.getAttribute('src')) {
                        el.setAttribute('src', src);
                    }
                } else {
                    // placeholder button → real iframe
                    var iframe = document.createElement('iframe');
                    iframe.src = src;
                    iframe.title = el.getAttribute('data-title') || 'Embedded content';
                    iframe.setAttribute('frameborder', '0');
                    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                    iframe.setAttribute('allowfullscreen', '');
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';
                    iframe.style.border = '0';
                    el.parentNode.replaceChild(iframe, el);
                }
            } else {
                if (el.tagName === 'IFRAME') {
                    // Replace live iframe with a placeholder (do not redirect).
                    var ph = document.createElement('div');
                    ph.className = 'jm-embed-placeholder';
                    ph.setAttribute('data-jm-optional-embed', '');
                    ph.setAttribute('data-src', src);
                    if (el.title) { ph.setAttribute('data-title', el.title); }
                    if (el.getAttribute('data-embed-title')) { ph.setAttribute('data-title', el.getAttribute('data-embed-title')); }
                    ph.innerHTML = '<div><p>Optional content (YouTube / Maps) is blocked until you accept.</p>' +
                        '<button type="button" class="btn btn-sm btn-primary" data-jm-allow-embed>Allow this embed</button></div>';
                    el.parentNode.replaceChild(ph, el);
                }
            }
        });
    }

    // Initial: convert optional iframes to placeholders until consent
    function gateOptionalEmbeds() {
        // Normalize view iframes (data-optional-embed + data-embed-src, live src)
        // into the gated shape before applying consent.
        document.querySelectorAll('iframe[data-optional-embed]').forEach(function (el) {
            var src = el.getAttribute('data-embed-src') || el.getAttribute('data-src') || el.getAttribute('src');
            if (src) {
                el.setAttribute('data-src', src);
                if (el.getAttribute('src')) {
                    el.removeAttribute('src');
                }
            }
        });

        var consent = readConsent();
        if (consent && consent.optional === true) {
            applyConsent(consent);
            hideBanner();
            return;
        }
        if (consent && consent.decided) {
            // Explicit reject — gate embeds, do not redirect
            applyConsent(consent);
            hideBanner();
            return;
        }
        // No decision yet: hide optional embeds, show banner
        applyConsent({ optional: false });
        showBanner();
    }

    banner.querySelector('[data-jm-cookie-accept]').addEventListener('click', function () {
        writeConsent({ decided: true, optional: true, essential: true });
    });

    banner.querySelector('[data-jm-cookie-reject]').addEventListener('click', function () {
        writeConsent({ decided: true, optional: false, essential: true });
    });

    customizeBtn.addEventListener('click', function () {
        var open = details.hidden;
        details.hidden = !open;
        customizeBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Allow individual embed without changing global preference for others
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-jm-allow-embed]');
        if (btn) {
            var ph = btn.closest('[data-jm-optional-embed]');
            if (ph) {
                var consent = readConsent() || { decided: true, essential: true };
                consent.optional = true;
                writeConsent(consent);
            }
        }
    });

    // Reopen settings from footer or anywhere
    document.querySelectorAll('[data-jm-cookie-settings]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var existing = readConsent();
            if (details) { details.hidden = false; }
            if (customizeBtn) { customizeBtn.setAttribute('aria-expanded', 'true'); }
            showBanner();
            // Reflect current choice on buttons is not needed; user picks again.
            void existing;
        });
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', gateOptionalEmbeds);
    } else {
        gateOptionalEmbeds();
    }
})();
</script>
