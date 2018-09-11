@extends('layouts.web')

@section('head_title')
    {{ $event->name }}
@endsection

@section('content')
    @include('components.alerts')

    <div class="container event-details">
        <div class="row mb-3">
            <div class="col">
                <h1>
                    {{ $event->name }}
                </h1>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <p>
                    {{ $event->description }}
                </p>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <table class="table table-striped">
                    <tr>
                        <th>
                            {{ __('Place') }}
                        </th>
                        <td>{{ $event->place }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Start date') }}
                        </th>
                        <td>{{ $event->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Time') }}
                        </th>
                        <td>{{ $event->time }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Duration (minutes)') }}
                        </th>
                        <td>{{ $event->duration_minutes }}</td>
                    </tr>
                    @if($event->max_guests_number)
                    <tr>
                        <th>
                            {{ __('Guests') }}
                        </th>
                        <td>
                            {{ count($event->guests) }}/{{ $event->max_guests_number }} ({{ round(count($event->guests) / $event->max_guests_number * 100, 2)}}%)
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($hasJoined)
            <div class="row mb-2">
                <div class="col">
                    <div class="alert alert-info">
                        {{ __('You did already joined to this event.') }}
                    </div>
                </div>
            </div>
        @endif

        @if($canJoin)
        <div class="row mb-2">
            <div class="col">
                <form method="post" action="{{ route('web.event.join', $event) }}">
                    @csrf
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Join Event') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div id="web-event-details-component"
             data-event-id="{{ $event->id }}"
             data-event-lat="{{ $event->geo_lat }}"
             data-event-lng="{{ $event->geo_lng }}"
        ></div>

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
    </div>
@endsection
