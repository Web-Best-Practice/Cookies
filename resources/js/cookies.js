(function () {
    const api = (window.WbpCookies = window.WbpCookies || {});

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function post(url) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken(),
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: '{}',
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Consent request failed');
            }
            return response.json();
        });
    }

    function updateConsent(state) {
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        if (typeof window.gtag === 'function') {
            window.gtag('consent', 'update', state);
        } else {
            gtag('consent', 'update', state);
        }
        api.consentGranted = state.analytics_storage === 'granted';
    }

    function runDeferredScripts() {
        document.querySelectorAll('template[data-cookies-scripts]').forEach(function (template) {
            const content = template.content.cloneNode(true);

            content.querySelectorAll('script').forEach(function (oldScript) {
                const newScript = document.createElement('script');
                Array.prototype.forEach.call(oldScript.attributes, function (attr) {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.replaceWith(newScript);
            });

            document.body.appendChild(content);
            template.remove();
        });
    }

    function hideBanner() {
        const banner = document.querySelector('[data-cookies-banner]');
        if (banner) {
            banner.classList.add('wbp-cookie--hidden');
            setTimeout(function () {
                banner.remove();
            }, 350);
        }
    }

    /**
     * Push a GA4 / GTM event from the browser.
     * Uses gtag when available, otherwise dataLayer.
     */
    api.track = function (name, params) {
        params = params || {};
        window.dataLayer = window.dataLayer || [];

        if (typeof window.gtag === 'function') {
            window.gtag('event', name, params);
            return;
        }

        window.dataLayer.push(
            Object.assign(
                {
                    event: name,
                },
                params
            )
        );
    };

    api.accept = function () {
        const url = (api.routes && api.routes.accept) || '';
        return post(url).then(function (data) {
            updateConsent(data.consent || {
                analytics_storage: 'granted',
                ad_storage: 'granted',
                ad_user_data: 'granted',
                ad_personalization: 'granted',
            });
            runDeferredScripts();
            hideBanner();
            window.dataLayer.push({ event: 'cookie_consent_accepted' });
        });
    };

    api.reject = function () {
        const url = (api.routes && api.routes.reject) || '';
        return post(url).then(function (data) {
            updateConsent(data.consent || {
                analytics_storage: 'denied',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
            });
            hideBanner();
            window.dataLayer.push({ event: 'cookie_consent_rejected' });
        });
    };

    document.addEventListener('click', function (event) {
        const accept = event.target.closest('[data-cookies-accept]');
        const reject = event.target.closest('[data-cookies-reject]');

        if (accept) {
            event.preventDefault();
            api.accept();
        }

        if (reject) {
            event.preventDefault();
            api.reject();
        }
    });
})();
