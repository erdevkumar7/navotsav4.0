<div class="d-flex gap-2">
    @can('delete category')
        <button type="button" class="btn btn-sm btn-danger delete-category"
            data-id="{{ $row->id }}"
            data-url="{{ route(routePrefix().'category.destroy', $row->id) }}">
            <i class="ri-delete-bin-2-line"></i>
        </button>
    @endcan
</div>
