@extends('emails.main')

@section('content')
    @component('emails.components.table')
        @component('emails.components.tableRow')
            @component('emails.components.tableRow')
                Hi {{ $customer->name }}, <br>
            @endcomponent

            @component('emails.components.tableRow')
                Here is a breakdown of your ride request:
            @endcomponent

            @component('emails.components.tableRowDivider')
            @endcomponent

            @component('emails.components.tableRow')
                @component('emails.components.tableContent')
                    <tr>
                        <td>Base Fare</td>
                        <td>{{ $booking->base_price }}</td>
                    </tr>

                    <tr>
                        <td>Distance Fare</td>
                        <td>{{ $booking->distance_price }}</td>
                    </tr>

                    <tr>
                        <td>Time Fare</td>
                        <td>{{ $booking->time_price }}</td>
                    </tr>

                    @component('emails.components.tableRowDivider')
                    @endcomponent

                    <tr>
                        <td>Total Price</td>
                        <td>{{ $booking->total + $booking->promo_amount }}</td>
                    </tr>

                    <tr>
                        <td>Discount Price</td>
                        <td>{{ $booking->promo_amount }}</td>
                    </tr>

                    @component('emails.components.tableRowDivider')
                    @endcomponent

                    <tr>
                        <td>Charged</td>
                        <td>{{ $booking->total }}</td>
                    </tr>

                    @component('emails.components.tableRowDivider')
                    @endcomponent

                    <tr>
                        <td>Ride Distance</td>
                        <td>{{ (int) $booking->distance_travel }}</td>
                    </tr>
                    <tr>
                        <td>Ride Duration</td>
                        <td>{{ (int) $booking->total_time }} minutes</td>
                    </tr>
                @endcomponent

            @endcomponent
        @endcomponent
    @endcomponent
@endsection
