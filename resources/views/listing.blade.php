@extends('app')

@section('content')

    <div class="title">
        <h1>All Videos</h1>
    </div>

    <div class="listing">
        @if ($videos)
            @include('partials.result-grid', ['videos' => $videos])
        @elseif ($message)
            <div class="message">
                <?php echo $message; ?>
            </div>
        @endif
    </div>

@endsection