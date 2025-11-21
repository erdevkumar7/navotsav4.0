@extends('layout.main')

@section('title', 'Organizers')

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
                                <li class="breadcrumb-item active">Organizers</li>
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

                                <a href="{{ route(routePrefix() . 'users.create') }}" class="btn btn-success">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Oganizer
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="organizersTable" class="table table-hover table-bordered table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>DOB</th>
                                        <th>Verification Status</th>
                                        <th>Suspend</th>
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
            let table = $('#organizersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(routePrefix() . 'organizers.data') }}",
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'verified',
                        name: 'verified',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'suspend',
                        name: 'suspend',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // ✅ Verify switch handler (delegated)
            $('#organizersTable').on('change', '.verified-check', function() {
                const userId = $(this).data('user-id');
                const isChecked = $(this).is(':checked');
                const baseUrl = "{{ url('/') }}";

                let url = `${baseUrl}/admin/users/${userId}/verify`;

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Success!', res.message, 'success');
                        }
                    }
                });
            });

            // ✅ Suspend switch handler (delegated)
            $('#organizersTable').on('change', '.suspend-check', function() {
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
                        // revert checkbox state if cancelled
                        $(checkbox).prop('checked', !isChecked);
                    }
                });
            });
        });
    </script>
@endpush
