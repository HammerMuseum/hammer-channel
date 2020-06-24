@extends('layouts.app')

@section('state')
<script type="text/javascript">
  window.INITIAL_STATE = "{!! addslashes(json_encode($state)) !!}";
</script>
@endsection

@section('content')
  <v-skip ref="skip" to="#main">
    Skip To Main Content
  </v-skip>
  <vue-progress-bar></vue-progress-bar>
  <vue-announcer></vue-announcer>
  <the-header></the-header>
  <transition name="fade">
    <main class="main" role="main">
      <router-view ref="routerView"></router-view>
    </main>
  </transition>
@endsection
