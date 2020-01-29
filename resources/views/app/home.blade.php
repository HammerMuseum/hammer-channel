@extends('app')

@section('content')

<home-component title="{{ $title }}"
                message="{{ $message }}"
                :pager='@json($pagerLinks)'
                :videos='@json($videos)'>
</home-component>

@endsection
