@extends('layouts.web')

@section('head_title')
    {{ $event->name }}
@endsection

@section('content')
    <div class="container event-details">
        <div class="row mb-3">
            <div class="col">
                <h1>
                    {{ $event->name }} - {{ $event->place }}
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

        @if($event->isPublic())
        <div class="row mb-2">
            <div class="col">
                <a href="#" class="btn btn-primary">
                    {{ __('Join Event') }}
                </a>
            </div>
        </div>
        @endif
    </div>
@endsection
