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
                            <span class="facets__label">{{ $facetLabel }}</span>
                            @foreach ($facet as $option)
                                <?php $date = new DateTime($option['key_as_string']) ?>
                                <a href="{{ $url }}?term={{ $term }}&facets=[0]date_recorded:{{ $date->format('Y') }}">
                                    {{ $date->format('Y') }}
                                </a>
                            @endforeach
                            <a href="/search?term={{ $term }}">Clear filter</a>
                        @endif
                    @endforeach
                    <div class="facets__sort">
                        <span class="facets__label">Order by</span>
                        <a href="{{ $clearedSortQuery }}&sort=date_recorded&order=asc">Date (ASC)</a>
                        <a href="{{ $clearedSortQuery }}&sort=date_recorded&order=desc">Date (DESC)</a>
                    </div>
                </div>
            @endif
        </div>
        @if ($videos)
            @include('partials.result-grid', ['videos' => $videos])
            @foreach ($pagerLinks as $label => $pagerLink)
                @if ($pagerLink)
                    <a href="{{ $clearedPageQuery }}{{ $pagerLink }}">{{ $label }}</a>
                @endif
            @endforeach
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection
