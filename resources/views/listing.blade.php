@extends('app')

@section('content')    
    <div class="listing">
        @if ($videos)
            @if ($title)
                <h1 class='title'>{{ $title }}</h1>
            @endif
            @include('partials.result-grid', ['videos' => $videos])
            @foreach ($pagerLinks as $label => $pagerLink)
                @if ($pagerLink)
                    <a href="{{ $pagerLink }}">{{ $label }}</a>
                @endif
            @endforeach
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection
