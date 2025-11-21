@extends('layout.main')

@section('title', 'Claime Requests')

@section('content')



    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Claim Requests</a>
                                </li>
                                <li class="breadcrumb-item active">Requests</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="thisTable" class="table table-hover table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Ticket number</th>
                                        <th scope="col">Event</th>
                                        <th scope="col">Status (Pending/Approved)</th>
                                        <th scope="col">Created At</th>

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
                ajax: "{{ route(routePrefix() . 'claim.request.data') }}",
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
                        data: 'ticket_number',
                        name: 'ticket_number'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }, {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });



            $('#thisTable').on('change', '.verified-check', function() {
                const claimId = $(this).data('claim-id');
                const isChecked = $(this).is(':checked');
                const baseUrl = "{{ url('/') }}";

                let url = `${baseUrl}/admin/claim-approve/${claimId}`;

                $.ajax({
                    url: url,
                    type: "PUT",
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

        });
    </script>
@endpush
