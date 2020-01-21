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
            <video-component 
                url="{{ $data['video_url'] }}"
                aid="{{ $data['asset_id'] }}"
                title="{{ $data['title'] }}"
                date="{{ $data['date_recorded'] }}"
                description="{{ $data['description'] }}"
                :tags='@json($data['tags'])'>
            </video-component>

            <noscript>
                <div class="hammer-video__information">
                    <div class="title">
                        <h1>{{ $data['title'] }}</h1>
                    </div>
                    <div class="date">
                        <?php $date = new DateTime($data['date_recorded']); ?>
                        {{ $date->format('l, F j, Y') }}
                    </div>
                    <div class="description">
                        {{ $data['description'] }}
                    </div>
                    <div class="keywords">
                        @if (isset($data['tags']) && !empty($data['tags']))
                            @foreach ($data['tags'] as $key => $tag)
                                <a href="/topics/{{ $tag }}">{{ $tag }}</a>@if ($key + 1 < count($data['tags'])), @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </noscript>
        @else
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>
@endsection
