<div class="result-grid">
    @if ($videos)
        @foreach ($videos as $video)
            @if (isset($video['asset_id']))
                <div class="result-item">
                    <a href="/video/{{ $video['title_slug'] }}">
                        <img class="result-item__image" src="{{ $video['thumbnail_url'] }}" />
                        <span class="result-item__title">{{ $video['title'] }}</span>
                    </a>
                </div>
            @endif
        @endforeach
    @endif
</div>
