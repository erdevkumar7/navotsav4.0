<div class="d-flex gap-2">
        <button type="button"
            class="btn btn-sm btn-danger delete-this"
            data-id="{{ $row->id }}"
            data-url="{{ route(routePrefix().'contact.destroy', $row->id) }}">
            <i class="ri-delete-bin-2-line"></i>
</button>
</div>
