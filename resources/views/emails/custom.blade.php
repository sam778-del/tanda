@extends('emails.main')

@section('content')
    @component('emails.components.table')
        @component('emails.components.tableRow')
            @component('emails.components.tableRow')
                {{ $title }}, <br>
            @endcomponent

            @component('emails.components.tableRow')
                {{ $description }}
            @endcomponent

            @component('emails.components.tableRowDivider')
            @endcomponent
        @endcomponent
    @endcomponent
@endsection
