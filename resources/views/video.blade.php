<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')
    <div class="video-content">
        @if ($data !== false)
            <div class="video">
                <video id="hammer-video-player" class="video-js hammer-video" controls type="video/mp4">
                    <source src="{{ $data['video_url'] }}" />
                </video>
            </div>
            <div class="hammer-video__information">
                <div class="title">
                    <h1>{{ $data['title'] }}</h1>
                </div>
                <div class="date">
                    {{ $data['date_recorded'] }}
                </div>
                <div class="description">
                    {{ $data['description'] }}
                </div>
            </div>
        @else
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

    {{--<script>--}}
        {{--(function(window, videojs) {--}}
            {{--var player = window.player = videojs('hammer-video-player');--}}
            {{--console.log('running');--}}
            {{--player.overlay({--}}
                {{--content: 'Default overlay content',--}}
                {{--debug: true,--}}
                {{--overlays: [{--}}
                    {{--content: 'The video is playing!',--}}
                    {{--start: 'play',--}}
                    {{--end: 'pause'--}}
                {{--}, {--}}
                    {{--start: 0,--}}
                    {{--end: 15,--}}
                    {{--align: 'bottom-left'--}}
                {{--}, {--}}
                    {{--start: 15,--}}
                    {{--end: 30,--}}
                    {{--align: 'bottom'--}}
                {{--}, {--}}
                    {{--start: 30,--}}
                    {{--end: 45,--}}
                    {{--align: 'bottom-right'--}}
                {{--}, {--}}
                    {{--start: 20,--}}
                    {{--end: 'pause'--}}
                {{--}]--}}
            {{--});--}}
        {{--}(window, window.videojs));--}}
    {{--</script>--}}

@endsection