@extends('layout.main')

@section('title', 'Edit Role Permissions')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Roles & Permissions</a></li>
                                <li class="breadcrumb-item active">Permissions</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">

                    <h4 class="mb-4">Permissions for Role:
                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                    </h4>

                    <form action="{{ route(routePrefix().'roles.update', $role->id) }}" method="POST">
                        @csrf

                        <div class="card shadow-sm border-0">
                            <div class="card-body">

                                @foreach ($permissions as $module => $perms)
                                    <div class="mb-4 p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">{{ ucfirst($module) }}</h5>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input module-checkbox" type="checkbox"
                                                    id="module-{{ $loop->index }}">
                                                <label class="form-check-label small text-muted"
                                                    for="module-{{ $loop->index }}">
                                                    Select All
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            @foreach ($perms as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input module-permission-{{ $loop->parent->index }}"
                                                            type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                                            {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                            {{ ucfirst($permission->name) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="text-start mt-3">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="bi bi-save me-2"></i> Save
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Handle "select all" per module
        document.querySelectorAll('.module-checkbox').forEach((moduleCheckbox, index) => {
            moduleCheckbox.addEventListener('change', function() {
                let checked = this.checked;
                document.querySelectorAll(`.module-permission-${index}`).forEach(cb => {
                    cb.checked = checked;
                });
            });
        });

        // Auto-check module select-all if all children are checked
        document.querySelectorAll('[class^="module-permission-"]').forEach(cb => {
            cb.addEventListener('change', function() {
                let index = this.className.match(/module-permission-(\d+)/)[1];
                let children = document.querySelectorAll(`.module-permission-${index}`);
                let allChecked = Array.from(children).every(c => c.checked);
                document.getElementById(`module-${index}`).checked = allChecked;
            });
        });
    </script>
@endpush
