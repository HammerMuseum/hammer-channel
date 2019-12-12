<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')
    <div class="video-content">
        @if ($data !== false)
            <div class="title">
                <h1>{{ $data->title }}</h1>
            </div>

            <div class="video">
                <video controls type="video/mp4" src="{{ $data->video_url }}" width="250">

                </video>
            </div>

            <div class="duration">
                {{ $data->duration }}
            </div>

            <div class="date">
                {{ $data->date_recorded }}
            </div>

            <div class="description">
                {{ $data->description }}
            </div>
        @else
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection