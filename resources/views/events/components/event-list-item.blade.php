<div class="col-md-4 mb-3 event-list-item">
    <div class="card">
        <div class="card-header">{{ $event->name }} at {{ $event->place }}</div>
        <div class="card-body">
            {{ $event->description }}
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('web.event.details', [ 'event' => $event ]) }}" class="btn btn-primary">
                {{ __('Read More') }}
            </a>
        </div>
    </div>
</div>
