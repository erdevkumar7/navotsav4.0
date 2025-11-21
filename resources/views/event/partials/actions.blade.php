<div class="d-flex gap-2">
    @can('view events')
        <a href="{{ route(routePrefix() . 'event.show', $event->id) }}" class="btn btn-sm btn-primary">
            <i class="ri-eye-line"></i>
        </a>
    @endcan

    @can('edit events')
        @if ($event->event_sold_tickets > 0)
            @if (auth()->user()->user_type == 1)
                <a href="{{ route(routePrefix() . 'event.edit', $event->id) }}" class="btn btn-sm btn-success">
                    <i class="ri-pencil-line"></i>
                </a>
            @else
                <button onclick="toastr.warning('Event booking started — you can’t edit this.')"
                    class="btn btn-sm btn-success">
                    <i class="ri-pencil-line"></i>
                </button>
            @endif
        @else
            <a href="{{ route(routePrefix() . 'event.edit', $event->id) }}" class="btn btn-sm btn-success">
                <i class="ri-pencil-line"></i>
            </a>
        @endif
    @endcan


    @can('delete events')
        @if ($event->event_sold_tickets > 0)
            @if (auth()->user()->user_type == 1)
                <button class="btn btn-sm btn-danger delete-this remove-item-btn" data-id="{{ $event->id }}"
                    data-url="{{ route(routePrefix() . 'events.destroy', $event->id) }}">
                    <i class="ri-delete-bin-2-line"></i>
                </button>
            @else
                <button class="btn btn-sm btn-danger"
                    onclick="toastr.warning('Event booking started — you can’t delete this.')">
                    <i class="ri-delete-bin-2-line"></i>
                </button>
            @endif
        @else
            <button class="btn btn-sm btn-danger delete-this remove-item-btn" data-id="{{ $event->id }}"
                data-url="{{ route(routePrefix() . 'events.destroy', $event->id) }}">
                <i class="ri-delete-bin-2-line"></i>
            </button>
        @endif
    @endcan

</div>
