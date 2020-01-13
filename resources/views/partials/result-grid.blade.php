<div class="result-grid">
    @if ($videos)
        @foreach ($videos as $video)
            @if (isset($video['asset_id']))
                <div class="result-item">
                    <a href="/video/{{ $video['asset_id'] }}">
                        <div class="result-item__thubmnail">
                            <img src="{{ $video['thumbnail_url'] }}" />
                        </div>
                        <div class="result-item__information">
                            <div class="result-item__title">
                                {{ $video['title'] }}
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    @endif
</div>
