<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/css/main.css" />
    <title>Hammer Museum | Video Archive</title>
    <script type="text/javascript">
        window.INITIAL_STATE = "{!! addslashes(json_encode($state)) !!}";
    </script>
  </head>

  <body>
    <div class="main" id="main-content">
      <the-header></the-header>
      <router-view></router-view>
      <the-footer></the-footer>
    </div>
    <script src="{{ (env('APP_ENV') === 'local') ? mix('js/app.js') : '/js/main.js' }}"></script>
  </body>
</html>
