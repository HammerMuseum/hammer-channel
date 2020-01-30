<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/css/main.css" />
    <title>Hammer Museum | Video Archive</title>
    <script type="text/javascript">
        window.__INITIAL_STATE__ = "{!! addslashes(json_encode($state)) !!}";
    </script>
  </head>

  <body>
    <div class="main" id="main-content">
      <router-view></router-view>
    </div>

    {{--<script type="text/javascript" src="/js/main.js"></script>--}}
     <script src="{{ (env('APP_ENV') === 'local') ? mix('js/app.js') : 'http://localhost:8080/js/app.js' }}"></script>

  </body>
</html>
