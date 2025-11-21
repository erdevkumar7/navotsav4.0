@extends('layout.main')

@section('title', 'Users')

@section('content')



    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">User Management</a>
                                </li>
                                <li class="breadcrumb-item active">Users</li>
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

                                <a href="{{ route(routePrefix().'users.create') }}" class="btn btn-success">
                                    <i class="ri-add-line align-bottom me-1"></i> Add User
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="usersTable" class="table table-hover table-bordered table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">DOB</th>
                                        <th scope="col">Suspend</th>
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
            let table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(routePrefix().'users.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'dob',
                        name: 'dob',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'suspend',
                        orderable: false,
                        searchable: false
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
            $('#usersTable').on('change', '.suspend-check', function() {
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

            // Delete user handler
            $('#usersTable').on('click', '.delete-user', function(e) {
                e.preventDefault();
                const button = $(this);
                const url = button.data('url');
                const row = table.row(button.closest('tr')); // Get the DataTable row

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will permanently delete the user!",
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
                                if (res.status) {
                                    // Remove the row from the DataTable and redraw
                                    row.remove().draw(false); // false keeps current page
                                    Swal.fire('Deleted!', 'User has been deleted.', 'success');
                                } else {
                                    Swal.fire('Error!', res.message || 'Something went wrong.', 'error');
                                }
                            },
                            error: function(err) {
                                Swal.fire('Error!', 'Unable to delete user.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
