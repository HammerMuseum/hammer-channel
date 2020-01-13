<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')
    <div class="video-content">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb__link">
                <span class="breadcrumb__text">Back to All Videos</span>
            </a>
        </div>
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
                <div class="keywords">
                    @foreach ($data['tags'] as $key => $tag)
                        <a href="">{{ $tag }}</a>@if ($key + 1 < count($data['tags'])), @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>
@endsection
