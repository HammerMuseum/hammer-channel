@extends('layouts.app')

@section('state')
<script type="text/javascript">
  window.INITIAL_STATE = "{!! addslashes(json_encode($state)) !!}";
</script>
@endsection

@section('content')
  <the-header></the-header>
  <transition name="fade">
    <main class="main" role="main">
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>
    </main>
  </transition>
@endsection
