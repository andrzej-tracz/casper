@if($event->guests && count($event->guests))
    <div class="row mb-2">
        <div class="col">
            <h2>{{ __('Guests') }}</h2>
            <table class="table">
                <tr>
                    <th>#</th>
                    <th></th>
                </tr>
                @foreach($event->guests as $guest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $guest->user->nickname }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif
