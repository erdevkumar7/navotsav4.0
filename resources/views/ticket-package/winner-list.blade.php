@extends('layout.main')

@section('title', 'Winners')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        {{-- <h4 class="mb-sm-0">Events</h4> --}}
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Tickets & Winners</a></li>
                                <li class="breadcrumb-item active">Winners</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <table id="winners-table"
                                class="table table-hover table-bordered table-striped table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Event Name</th>
                                        <th>Ticket Number</th>
                                        <th>User</th>
                                        <th>Phone</th>
                                        <th>winning Price</th>
                                        <th>Annouce At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Initialize DataTable and store in a variable
            var table = $('#winners-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route(routePrefix() . 'winners.data') }}",
                    data: function(d) {
                        d.event_id = $('#event-filter').val(); // send selected event ID
                    }
                },
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'event',
                        name: 'event.title'
                    },
                    {
                        data: 'ticket_number',
                        name: 'ticket.ticket_number'
                    },
                    {
                        data: 'user',
                        name: 'user.name'
                    },
                    {
                        data: 'phone',
                        name: 'user.phone'
                    },

                    {
                        data: 'price',
                        name: 'ticket.price'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                order: [
                    [6, 'desc']
                ]
            });


        });
    </script>
@endpush
