@extends('layouts.app')

@section('content')
<div id="start-of-content" class="page-wrapper page-wrapper--full">
  @foreach ($state['videos'] as $category)
  <h2 class="carousel__title">
    <a href="/search?topics={{ $category['id'] }}">{{ $category['label'] }}</a>
  </h2>
  <div class="carousel-wrapper carousel-wrapper--static">
    @foreach ($category['hits'] as $video)
    <div class="ui-card">

      @if(isset($video['asset_id']) && isset($video['title_slug']))
      <a href="/video/{{$video['asset_id']}}/{{$video['title_slug']}}">

        <div class="ui-card__thumbnail">

        @isset($video['thumbnailId'])
          <img src="/images/d/medium/{{$video['thumbnailId']}}.jpg" alt="" class="ui-card__thumbnail-image">
        @endisset
        </div>

        <h2 class="ui-card__title">
        @isset($video['title'])
          <span>{{ $video['title'] }}</span>
        @endisset
        </h2>

        @isset($video['description'])
        <p class="ui-card__description">{{ html_entity_decode(strip_tags(Str::of($video['description'])->limit(180))) }}</p>
        @endisset

        @isset($video['duration'])
          <span class="ui-card__duration">{{ $video['duration']}}</span>
        @endisset
      </a>
      @endif
    </div>
    @endforeach
  </div>
  @endforeach
</div>
@endsection
