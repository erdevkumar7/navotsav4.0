@extends('layout.main')

@section('title', 'Buyers')

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
                                <li class="breadcrumb-item active">Buyers</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <table id="buyers-table" class="table table-hover table-bordered table-striped table-responsive"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
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
        $(function() {
            $('#buyers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(routePrefix().'buyers.data') }}", // route pointing to controller
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                ]
            });
        });
    </script>

    <script>
        const suspendCheckboxes = document.querySelectorAll('.suspend-check');

        suspendCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.userId; // Get the user ID from the data attribute
                const isChecked = this.checked;

                let title, text, confirmButtonText, icon;

                if (isChecked) {
                    // If the checkbox is being checked (suspended)
                    title = 'Are you sure you want to suspend this user?';
                    text = "The user will not be able to log in.";
                    confirmButtonText = 'Yes, suspend it!';
                    icon = 'warning';
                } else {
                    // If the checkbox is being unchecked (unsuspended)
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

                        console.log(
                            `User ID: ${userId}, New Status: ${isChecked ? 'Suspended' : 'Active'}`
                        );
                        const baseUrl = "{{ url('/') }}";
                        let url;
                        if (isChecked) {
                            url = `${baseUrl}/admin/users/${userId}/suspend`;
                        } else {
                            url = `${baseUrl}/admin/users/${userId}/unsuspend`;
                        }

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

                        })


                    } else {
                        // User canceled, so revert the checkbox state
                        this.checked = !isChecked;
                    }
                });
            });
        });

        const verifyCheckboxes = document.querySelectorAll('.verified-check');

        verifyCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.userId; // Get the user ID from the data attribute
                const isChecked = this.checked;
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
                            Swal.fire(
                                'Success!',
                                `${res.message}`,
                                'success'
                            );
                        }
                    }

                })


            });
        });
    </script>
@endpush
