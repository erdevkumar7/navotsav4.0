@extends('layout.main')

@section('title', 'Contact Leads')

@section('content')



    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Contact Leads</a>
                                </li>
                                <li class="breadcrumb-item active">Contact's</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="thisTable" class="table table-hover table-bordered table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone No</th>
                                        <th scope="col">Message</th>
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
                ajax: "{{ route(routePrefix() . 'contact.data') }}",
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
                    {
                        data:'body',
                        name: 'message'
                    },
                    {
                        data:'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });



// Delete blog handler
$('#thisTable').on('click', '.delete-this', function (e) {
    e.preventDefault();
    const button = $(this);
    const url = button.data('url');
    const row = table.row(button.closest('tr')); // DataTables row reference

    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete the contact lead!",
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
                success: function (res) {
                    if (res.success || res.status) {
                        row.remove().draw(false);
                        Swal.fire('Deleted!', res.message || 'Blog has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', res.message || 'Something went wrong.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Unable to delete blog.', 'error');
                }
            });
        }
    });
});

        });
    </script>
@endpush
