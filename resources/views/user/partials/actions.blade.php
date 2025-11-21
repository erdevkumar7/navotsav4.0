<div class="d-flex gap-2">
    @can('edit users')
        <a href="{{ route(routePrefix().'users.edit', $row->id) }}" class="btn btn-sm btn-success">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan

    @can('delete users')
        <button type="button" class="btn btn-sm btn-danger delete-user" data-id="{{ $row->id }}"
            data-url="{{ route(routePrefix().'users.destroy', $row->id) }}">
            <i class="ri-delete-bin-2-line"></i>
        </button>
    @endcan
</div>
