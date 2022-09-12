@extends('emails.main')

@section('content')
    @component('emails.components.table')
        @component('emails.components.tableRow')
            @component('emails.components.tableRow')
                Hi {{ $user->first_name }}, <br>
            @endcomponent

            @component('emails.components.tableRow')
                {{ $mailData->body }}
            @endcomponent

            @component('emails.components.tableRowDivider')
            @endcomponent
        @endcomponent
    @endcomponent
@endsection
