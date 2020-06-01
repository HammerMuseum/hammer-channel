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

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest" crossorigin="use-credentials">>
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ee2a7b">
  <meta name="msapplication-TileColor" content="#ee2a7b">
  <meta name="theme-color" content="#262626">

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
      </main>
    </transition>
  </div>
  <script src="{{ (env('APP_ENV') === 'local') ? mix('js/app.js') : '/js/main.js' }}"></script>
</body>

</html>