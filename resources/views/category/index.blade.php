@extends('layout.main')

@section('title', 'Event Categories')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Category Management</a></li>
                                <li class="breadcrumb-item active">Categories</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="categoryTable" class="display table table-bordered dt-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

        </div>
        <!-- container-fluid -->
    </div>

@endsection



@push('scripts')
<script>
$(document).ready(function() {
    // Initialize category DataTable
    let table = $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route(routePrefix().'category.data') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });

    // Delete category handler (delegated)
    $('#categoryTable').on('click', '.delete-category', function(e) {
        e.preventDefault();
        const button = $(this);
        const url = button.data('url');
        const row = table.row(button.closest('tr'));

        Swal.fire({
            title: 'Are you sure?',
            text: "This action will permanently delete the category!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(res) {
                        if (res.status) {
                            row.remove().draw(false);
                            Swal.fire('Deleted!', 'Category has been deleted.', 'success');
                        } else {
                            Swal.fire('Error!', res.message || 'Something went wrong.', 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Unable to delete category.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush

