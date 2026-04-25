<?php
// Add GTM script to head
function add_gtm_script_to_head() {
  ?>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-NT5R3QH');
  </script>
  <!-- End Google Tag Manager -->
  <?php
}
add_action('wp_head', 'add_gtm_script_to_head');

/**
 * Add Scripts to head tag
 */
function add_facebook_pixel_scripts() {
  ?>
	<!-- Meta Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		 if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		 n.queue=[];t=b.createElement(e);t.async=!0;
		 t.src=v;s=b.getElementsByTagName(e)[0];
		 s.parentNode.insertBefore(t,s)}(window, document,'script', 'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1676192066238162');
		fbq('track', 'PageView');
	</script>
	<noscript>
		<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1676192066238162&ev=PageView&noscript=1" />
	</noscript>
	<!-- End Meta Pixel Code -->
  <?php
}

add_action('wp_head', 'add_facebook_pixel_scripts');

/**
 * Output the Google Tag Manager noscript code.
 *
 * @return void
 */
function easyline_add_gtm_noscript() {
  echo <<<HTML
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NT5R3QH" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  HTML;
}
add_action('wp_body_open', 'easyline_add_gtm_noscript');