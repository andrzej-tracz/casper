@extends('layouts.web')

@section('head_title')
    {{ $event->name }}
@endsection

@section('content')
    @include('components.alerts')
    <div class="container event-details">
        @include('events.components.event-basic-details', ['event' => $event])
        @include('events.components.event-guests-list', ['event' => $event])
    </div>
@endsection
