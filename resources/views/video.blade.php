<?php
/**
 * @var $data array
 */
?>

@extends('app')

@section('content')

    <div class="main">

        <div class="title">
            <h1><?php echo $data['title'] ?></h1>
        </div>

        <div class="video">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/qTgBSkqyYZs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <div class="duration">
            <?php echo $data['duration']; ?>
        </div>

        <div class="date">
            <?php echo $data['date_recorded']; ?>
        </div>

        <div class="description">
            <?php echo $data['description']; ?>
        </div>

    </div>

@endsection