<?php
/** @var $videos array
 *  @var $message string | boolean
 *  @var $title string
 * @var $term string
 */
?>

@extends('app')

@section('content')

    <div class="listing">
        <div class="title">
            @if ($title)
                <h1>{{ $title }}</h1>
            @endif
        </div>
        <div class="search">
            <form class="search-box" action="/search">
                <input type="search" name="term" />
                <button type="submit">Search</button>
            </form>
            <a href="/">Clear</a>
        </div>
        <div class="filters">
            <div class="date">
                <form action="/search/sort/{{ $term }}/date_recorded">
                    <select type="dropdown" name="order">
                        <option value="asc">Date (ASC)</option>
                        <option value="desc">Date (DESC)</option>
                    </select>
                    <button type="submit">Sort</button>
                </form>
            </div>
        </div>
        @if ($videos)
            @include('partials.result-grid', ['videos' => $videos])
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection
