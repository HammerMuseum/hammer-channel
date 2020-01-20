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
        <div class="filters">
            @if ($facets)
                <div class="facets">
                    <h2>Filter by</h2>
                    @foreach ($facets as $facetLabel => $facet)
                        @if ($facetLabel == 'Year Recorded')
                            <form action="/search/filter/{{ $term }}">
                                <label for="date_recorded">{{ $facetLabel }}</label>
                                <select name="date_recorded" id="year">
                                    @foreach ($facet as $option)
                                        <?php $date = new DateTime($option['key_as_string']) ?>
                                        <option value="{{ $date->format('Y') }}">{{ $date->format('Y') }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Filter</button>
                                <a href="/search?term={{ $term }}">Clear filter</a>
                            </form>
                        @endif
                    @endforeach
                </div>
            @endif
            <div class="date">
                <form action="/search/sort/{{ $term }}/date_recorded">
                    <label for="order">Order by</label>
                    <select type="dropdown" name="order">
                        <option value="date_recorded_asc">Date (ASC)</option>
                        <option value="date_recorded_desc">Date (DESC)</option>
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
