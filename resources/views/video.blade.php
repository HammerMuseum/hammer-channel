<?php
/**
 * @var $data stdClass
 */
?>

@extends('app')

@section('content')

    <div class="main">

        @if ($data !== false && !is_null($data))
            <div class="video">
                <video controls type="video/mp4" src="<?php echo $data->video_url; ?>" width="250"></video>
            </div>

            <div class="title">
                <h1><?php echo $data->title ?></h1>
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