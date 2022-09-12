@if ($message = session('success'))
    @if(is_array($message))
        @foreach( $message as $msg )
            <div class="alert alert-success dark alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        data-bs-original-title title></button>
                <strong>{{ $msg }}</strong>
                @if( !$loop->last )
                    <br>
                @endif
            </div>
        @endforeach
    @else
        <div class="alert alert-success dark alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    data-bs-original-title title></button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
@endif


@if ($message = session('errors'))
    @foreach( $message->all() as $msg )
        <div class="alert alert-danger dark alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    data-bs-original-title title></button>
            <strong>{{ $msg }}</strong>
            @if( !$loop->last )
                <br>
            @endif
        </div>
    @endforeach
@endif


@if ($message = session('warning'))
    @if(is_array($message))
        @foreach( $message as $msg )
            <div class="alert alert-warning dark alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        data-bs-original-title title></button>
                <strong>{{ $msg }}</strong>
                @if( !$loop->last )
                    <br>
                @endif
            </div>
        @endforeach
    @else
        <div class="alert alert-warning dark alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    data-bs-original-title title></button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
@endif


@if ($message = session('info'))
    @if(is_array($message))
        @foreach( $message as $msg )
            <div class="alert alert-primary dark alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                        data-bs-original-title title></button>
                <strong>{{ $msg }}</strong>
                @if( !$loop->last )
                    <br>
                @endif
            </div>
        @endforeach
    @else
        <div class="alert alert-primary dark alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    data-bs-original-title title></button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
@endif
