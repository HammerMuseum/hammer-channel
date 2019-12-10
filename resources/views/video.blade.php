<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')

    <div class="main">

        @if ($data !== false)
            <div class="title">
                <h1><?php echo $data->title ?></h1>
            </div>

            <div class="video">
                <video class="video-js" controls type="video/mp4" width="250">
                    <source src="<?php echo $data->video_url; ?>" type="video/mp4" />

                </video>
            </div>

            <div class="duration">
                <?php echo $data->duration; ?>
            </div>

            <div class="date">
                <?php echo $data->date_recorded; ?>
            </div>

            <div class="description">
                <?php echo $data->description; ?>
            </div>
        @else
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection