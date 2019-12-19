<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')
    <div class="video-content">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb__link">Back to All Videos</a>
        </div>
        @if ($data !== false)
            <div class="video">
                <video class="video-js hammer-video" controls type="video/mp4">
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

@endsection