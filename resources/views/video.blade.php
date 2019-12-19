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
                <span class="breadcrumb__icon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="32"><path d="M.311 30.334L15.34 15.307l1.727 1.727L2.038 32.061.311 30.334zM.095 1.694L1.822-.033l16.236 16.236-1.727 1.727L.095 1.694z"/></svg>
                </span>
                <span class="breadcrumb__text">Back to All Videos</span>
            </a>
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