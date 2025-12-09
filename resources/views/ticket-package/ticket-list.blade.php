@extends('layout.main')

@section('title', 'Tickets')

<style>
    .ticket-offline-book {
        width: 100%;
    }

    .ticket-offline-book .card {
        overflow-y: scroll;
    }

    .ticket-offline-book tbody td {
        font-size: 13px;
    }

    div#newEventTable_wrapper th.sorting {
        font-size: 14px !important;
    }
</style>

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        {{-- <h4 class="mb-sm-0">Events</h4> --}}
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Tickets & Bookings</a></li>
                                <li class="breadcrumb-item active">Tickets</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 ticket-offline-book">
                    <div class="card">

                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped" id="newEventTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        {{-- <th>Event Name</th> --}}
                                        {{-- <th>Pass ID</th> --}}
                                        {{-- <th>Pass Name</th> --}}
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Jnv</th>
                                        <th>Passout Year</th>
                                        <th>Booked At</th>
                                        <th>Payment status</th>
                                        {{-- <th>Transaction ID</th> --}}
                                        <th>Edit Details</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>Payment Proof</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($events as $key => $event)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            {{-- <td>NAVLAY 1.0</td> --}}
                                            {{-- <td>{{ $event->pass_id }}</td> --}}
                                            {{-- <td>{{ $event->pass_name }}</td> --}}
                                            <td>{{ $event->user_name }}</td>
                                            <td>{{ $event->email }}</td>
                                            <td>{{ $event->mobile }}</td>
                                            <td>{{ $event->jnv }}</td>
                                            <td>{{ $event->year }}</td>
                                            <td>{{ $event->created_at }}</td>
                                            <td>
                                                <form action="{{ route('admin.ticket.updateStatus', $event->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    {{-- <select name="payment_status"
                                                        class="form-select form-select-sm status-select"
                                                        data-status="{{ $event->payment_status }}"
                                                        onchange="handleStatusChange(this, {{ $event->id }})"
                                                        style="background: {{ $event->payment_status == 'success' ? '#28a745' : '#ffc107' }}"> --}}


                                                    <select name="payment_status"
                                                        class="form-select form-select-sm status-select"
                                                        data-status="{{ $event->payment_status }}"
                                                        onchange="handleStatusChange(this, {{ $event->id }})"
                                                        {{ $event->payment_status === 'success' ? 'disabled' : '' }}
                                                        style="background: {{ $event->payment_status == 'success' ? '#28a745' : '#ffc107' }}">

                                                        <option value="pending" style="background: #ffc107"
                                                            {{ $event->payment_status == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>

                                                        <option value="success" style="background: #28a745"
                                                            {{ $event->payment_status == 'success' ? 'selected' : '' }}>
                                                            Success
                                                        </option>

                                                    </select>
                                                </form>
                                            </td>
                                            {{-- <td>{{ $event->merchant_transaction_id }}</td> --}}
                                            {{-- {{ asset('assets/images/logo.png') }} --}}

                                            <td>
                                                <a href="{{ route('admin.ticket.edit', $event->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    Edit
                                                </a>
                                            </td>
                                            <td>{{ $event->qty }}</td>
                                            <td>{{ $event->amount }}</td>

                                            <td>
                                                @if ($event->payment_status === 'success' && $event->payment_image)
                                                    <a href="{{ asset('assets/payment_proofs/' . $event->payment_image) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        View
                                                    </a>
                                                @else
                                                    <span class="text-muted">--</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{-- {{$events->links()}} --}}
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

        </div>

        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="paymentSuccessForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Payment (Success)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <!-- Payment Type -->
                            <div class="mb-3">
                                <label>Payment Type</label>
                                <select id="payment_type" class="form-select" name="payment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="online">Online Payment</option>
                                    {{-- <option value="offline">Offline Payment</option> --}}
                                </select>
                            </div>

                            <!-- ONLINE FIELDS -->
                            <div id="onlineFields" style="display:none;">
                                <div class="mb-3">
                                    <label>Transaction ID</label>
                                    <input type="text" id="transaction_id" name="online_transaction_id"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Upload Payment Proof</label>
                                    <input type="file" id="payment_image" name="payment_image" class="form-control"
                                        required>

                                    <input type="hidden" name="payment_status" value="success">
                                </div>
                            </div>

                            <!-- OFFLINE TEXT -->
                            <div id="offlineMsg" class="alert alert-info" style="display:none;">
                                No additional details required for offline payment.
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update Payment</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedEventId = null;

        function handleStatusChange(select, id) {
            let status = select.value;
            if (status === "success") {
                selectedEventId = id;

                // Set modal form action
                let url = "/admin/ticket/update-status/" + id;
                $("#paymentSuccessForm").attr("action", url);
                $('#paymentModal').modal('show');
            } else {
                select.form.submit(); // Only for pending / failed
            }
        }

        // Show/hide fields depending on payment type
        $("#payment_type").on("change", function() {
            let val = $(this).val();

            if (val === "online") {
                $("#onlineFields").show();
                $("#offlineMsg").hide();
            } else if (val === "offline") {
                $("#onlineFields").hide();
                $("#offlineMsg").show();
            } else {
                $("#onlineFields").hide();
                $("#offlineMsg").hide();
            }
        });
    </script>

    <script>
        document.querySelectorAll(".status-select").forEach(select => {
            updateSelectColor(select);

            select.addEventListener("change", function() {
                updateSelectColor(this);
            });
        });

        function updateSelectColor(select) {
            let status = select.value;

            if (status === "success") {
                select.style.background = "#28a745"; // green
            } else if (status === "pending") {
                select.style.background = "#ffc107"; // yellow
            } else {
                select.style.background = "#000"; // default
            }
        }
    </script>


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

    <script>
        $(document).ready(function() {
            $('#newEventTable').DataTable({
                pageLength: 25
            });
        });
    </script>
@endpush
