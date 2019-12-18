<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')
    <div class="video-content">
        @if ($data !== false)

            <video-component video_url="{{ $data['video_url'] }}"></video-component>

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
@endsection