@extends('layouts.web')

@section('content')
    @include('components.alerts')
    <div class="container">
        @if(!empty($events))
            <div class="row mb-4">
                <div class="col">
                    <h1>{{ __('Upcoming events') }}</h1>
                </div>
            </div>
            <div class="row align-items-stretch">
                @foreach($events as $event)
                    @component('events.components.event-list-item', ['event' => $event])
                    @endcomponent
                @endforeach
            </div>
            <div class="row">
                <div class="col">
                    {{ $events->render() }}
                </div>
            </div>
        @else
            <div class="row justify-content-center align-items-stretch">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ __('No events') }}</div>
                        <div class="card-body">
                            {{ __('Sorry, there is no upcoming events.')  }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
