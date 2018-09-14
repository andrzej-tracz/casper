@extends('layouts.web')

@section('head_title')
    {{ __('Invitation to') }} - {{ $event->name }}
@endsection

@section('content')
    @include('components.alerts')

    <div class="container event-details">

        @if($invitation->isNew())
        <div class="alert alert-info">
            You have been invited to the following event
        </div>
        <div class="row">
            <div class="col text-right">
                <form method="post" action="{{ route('events.invitation.accept', $invitation) }}">
                    @method('PUT')
                    @csrf
                    <button class="btn btn-primary" type="submit">Accept Invitation</button>
                </form>
            </div>
        </div>
        @endif

        @include('events.components.event-basic-details', ['event' => $event])
        @include('events.components.event-guests-list', ['event' => $event])
    </div>
@endsection
