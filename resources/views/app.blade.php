<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:url" content="{{ $metadata['url'] }}" />
  <meta name="twitter:title" content="{{ $metadata['title'] }}" />
  <meta name="twitter:description" content="{{ $metadata['description'] }}" />
  <meta name="twitter:image" content="{{ $metadata['image'] }}" />

  <meta property="og:site_name" content="{{ $metadata['site_name'] }}" />
  <meta property="og:url" content="{{ $metadata['url'] }}" />
  <meta property="og:title" content="{{ $metadata['title'] }}" />
  <meta property="og:description" content="{{ $metadata['description'] }}" />
  <meta property="og:image" content="{{ $metadata['image'] }}" />
  <meta property="og:image:type" content="image/jpg" />
  <meta property="og:image:width" content="320" />
  <meta property="og:image:height" content="180" />

  <link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="/icons/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
  <link rel="manifest" href="/icons/manifest.json" crossorigin="use-credentials">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/icons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <link rel="stylesheet" type="text/css" href="/css/main.css" />
  <link rel="stylesheet" href="https://use.typekit.net/onc8trv.css">

  <title>Hammer Museum | Video Archive</title>
  <script type="text/javascript">
    window.INITIAL_STATE = "{!! addslashes(json_encode($state)) !!}";
  </script>
</head>

<body>
  <div id="app">
    <the-header></the-header>
    <transition name="fade">
      <main class="main" role="main">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
      </main>
    </transition>
  </div>
  <script src="{{ (env('APP_ENV') === 'local') ? mix('js/app.js') : '/js/main.js' }}"></script>
</body>

</html>