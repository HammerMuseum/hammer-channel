@extends('app')

@section('content')

    <div class="listing grid">
        <div class="title">
            <h1>All Videos</h1>
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