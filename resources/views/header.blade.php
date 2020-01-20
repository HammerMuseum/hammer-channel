<div class="header">
    <form action="/search" method="get" role="search">
        <div class="search">
            <label for="search-main" class="search__label"></label>
            <div class="search__item-wrapper">
                <input type="search" value="{{ request()->term }}" id="search-main" name="term" title="Search" aria-controls="" placeholder="Search" class="search__item search__input">
                <div class="search__item search__submit-wrapper">
                    <button type="submit" class="search__submit">Search</button>
                </div>
            </div>
        </div>
        @if ($show_clear ?? '')<a href="/">Clear search</a>@endif
    </form>
</div>
