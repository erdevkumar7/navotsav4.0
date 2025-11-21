@can('edit events')
    @if ($event->event_sold_tickets > 0)
        <select class="form-select form-select-sm"
         onclick="toastr.warning('Event booking started — you can’t change the status.')" disabled>
            <option selected>{{ $event->is_publish ? 'Published' : 'Draft' }}</option>
        </select>
    @else
        <select class="form-select form-select-sm change-status" data-id="{{ $event->id }}">

        @if ($event->is_publish)
             <option value="true" selected>Published</option>
        @else
         <option value="true" selected>Publish</option>
         <option value="false" selected>Draft</option>
        @endif
            {{-- <option value="true" {{ $event->is_publish ? 'selected' : '' }}>Published</option>
            <option value="false" {{ !$event->is_publish ? 'selected' : '' }}>Draft</option> --}}
        </select>
    @endif
@else
    <span>{{ $event->is_publish ? 'Published' : 'Draft' }}</span>
@endcan
