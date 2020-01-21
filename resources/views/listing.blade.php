@extends('app')

@section('content')    
    <div class="listing">
        @if ($videos)
            @if ($title)
                <h1 class='title'>{{ $title }}</h1>
            @endif
            @include('partials.result-grid', ['videos' => $videos])
            @if ($prevLink)
                <a href="{{ $prevLink }}">Previous</a>
            @endif
            @if ($nextLink)
                <a href="{{ $nextLink }}">Next</a>
            @endif
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection
