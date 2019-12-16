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
        @if ($videos)
            @include('partials.result-grid', ['videos' => $videos])
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection