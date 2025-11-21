@extends('layout.main')

@section('title', 'Tickets')

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
                                <li class="breadcrumb-item active">Tickets</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Event Name</th>
                                        {{-- <th>Pass ID</th> --}}
                                        <th>Pass Name</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Jnv</th>
                                        <th>Passout Year</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        {{-- <th>Transaction ID</th> --}}
                                        <th>Booked At</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($events as $key => $event)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>NAVOTSAV 4.0</td>
                                            {{-- <td>{{ $event->pass_id }}</td> --}}
                                            <td>{{ $event->pass_name }}</td>
                                            <td>{{ $event->user_name }}</td>
                                            <td>{{ $event->email }}</td>
                                            <td>{{ $event->mobile }}</td>
                                            <td>{{ $event->jnv }}</td>
                                            <td>{{ $event->year }}</td>
                                            <td>{{ $event->qty }}</td>
                                            <td>{{ $event->amount }}</td>
                                            {{-- <td>{{ $event->merchant_transaction_id }}</td> --}}
                                            <td>{{ $event->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
            const urlParams = new URLSearchParams(window.location.search);
            let eventIdFromUrl = urlParams.get('event_id');
            var table = $('#tickets-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route(routePrefix() . 'ticket.data') }}`,
                    data: function(d) {
                        d.event_id = eventIdFromUrl // send selected event ID
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
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }, {
                        data: 'event_title',
                        name: 'events.title',
                        visible: eventIdFromUrl ? false : true
                    },

                    {
                        data: 'ticket_number',
                        name: 'tickets.ticket_number'
                    },

                    {
                        data: 'sold_tickets',
                        name: 'sold_tickets',
                        searchable: false
                    },

                    {
                        data: 'price',
                        name: 'tickets.price'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'tickets.status'
                    },
                    {
                        data: 'buyer_name',
                        name: 'buyer.name'
                    },
                    {
                        data: 'book_from',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'tickets.created_at'
                    }
                ],
            });

            // Listen for dropdown change and reload table
            $('#event-filter').on('change', function() {
                table.ajax.reload(); // reload table data based on new filter
            });
        });
    </script>
@endpush
