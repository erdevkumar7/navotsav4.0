@extends('layout.main')

@section('title', 'Blogs')

@section('content')



    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Blog Management</a>
                                </li>
                                <li class="breadcrumb-item active">Blogs</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0"></h4>
                            <div>

                                <a href="{{ route(routePrefix() . 'blog.create') }}" class="btn btn-success">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Blog
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="thisTable" class="table table-hover table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th></th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                            </table>

                            <div class="col-md-12 text-right">
                                {{-- {{ $users->links() }} --}}
                            </div>
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
            let table = $('#thisTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(routePrefix() . 'blog.data') }}",
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });

            // Suspend switch handler (delegated)
            $('#thisTable').on('change', '.suspend-check', function() {
                const checkbox = this;
                const userId = $(this).data('user-id');
                const isChecked = $(this).is(':checked');

                let title, text, confirmButtonText, icon;

                if (isChecked) {
                    title = 'Are you sure you want to suspend this user?';
                    text = "The user will not be able to log in.";
                    confirmButtonText = 'Yes, suspend it!';
                    icon = 'warning';
                } else {
                    title = 'Are you sure you want to un-suspend this user?';
                    text = "The user will be able to log in again.";
                    confirmButtonText = 'Yes, un-suspend it!';
                    icon = 'question';
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        const baseUrl = "{{ url('/') }}";
                        let url = isChecked ?
                            `${baseUrl}/admin/users/${userId}/suspend` :
                            `${baseUrl}/admin/users/${userId}/unsuspend`;

                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                if (res.status) {
                                    Swal.fire(
                                        'Success!',
                                        `User has been ${isChecked ? 'suspended' : 'activated'}.`,
                                        'success'
                                    );
                                }
                            }
                        });
                    } else {
                        // Revert checkbox state if cancelled
                        $(checkbox).prop('checked', !isChecked);
                    }
                });
            });

            // Delete blog handler
            $('#thisTable').on('click', '.delete-this', function(e) {
                e.preventDefault();
                const button = $(this);
                const url = button.data('url');
                const row = table.row(button.closest('tr')); // DataTables row reference

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will permanently delete the blog!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                if (res.success || res.status) {
                                    row.remove().draw(false);
                                    Swal.fire('Deleted!', res.message ||
                                        'Blog has been deleted.', 'success');
                                } else {
                                    Swal.fire('Error!', res.message ||
                                        'Something went wrong.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'Unable to delete blog.', 'error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
