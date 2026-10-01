@php($syrenisEnabled = ($syrenis ?? true) && config('services.syrenis.enabled'))
<script>
  window.dataLayer = window.dataLayer || [];
</script>
@if($syrenisEnabled)
  <script src="https://cdn-us1.syrenis.io/cmp/embed.js" data-license="{{ config('services.syrenis.license') }}" data-banner="{{ config('services.syrenis.banner') }}"></script>
@endif
@if(config('services.gtm.enabled'))
  <script>
    var gtmLoaded = false;
    function loadGTM() {
      if (gtmLoaded) {
        return;
      }

      gtmLoaded = true;

      (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', @js(config('services.gtm.container_id')));
    }

    @if($syrenisEnabled)
      // GTM must wait for the CMP to ensure default consents are set before tags fire.
      document.addEventListener('syrenis-cmp-ready', loadGTM, {once: true});

      // If the CMP fails to load, continue to load GTM.
      setTimeout(function () {
        if (!gtmLoaded) {
          console.warn('syrenis-cmp-ready not received within timeout');
          loadGTM();
        }
      }, 5000);
    @else
      loadGTM();
    @endif
  </script>
@endif
