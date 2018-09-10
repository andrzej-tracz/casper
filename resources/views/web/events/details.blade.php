@extends('layouts.web')

@section('content')
    <div class="container event-details">
        <div class="row mb-5">
            <div class="col">
                {{ $event->title }}
            </div>
        </div>
    </div>
@endsection
