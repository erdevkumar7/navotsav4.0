@extends('layout.main')

@section('title', 'View Event')

@section('content')


    <!-- Internal CSS -->
    <style>
        .evt_sidebar .card-body {
            padding: 1rem 1.2rem;
        }

        .evt_sidebar .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 8px;
        }

        .evt_sidebar .detail-item:last-child {
            border-bottom: none;
        }

        .evt_sidebar .detail-item i {
            font-size: 30px;
            color: #f0ad4e;
            margin-right: 12px;
        }

        .evt_sidebar .label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
        }

        .evt_sidebar .detail-item div {
            font-size: 14px;
            /* bigger text */
            color: #333;
        }

        .banner {
            position: relative;
            width: 100%;
            height: 300px;
            /* adjust height as needed */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 5px;
            /* keep rounded look */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.788);
            /* dark overlay for contrast */
        }

        .banner .banner-title {
            position: relative;
            color: #fff;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 0px 2px 6px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }



        .event-prices {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }

        .price-table {
            display: flex;
            flex-direction: column;
        }

        .table-header,
        .table-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .table-header {
            font-weight: 600;
            border-bottom: 2px solid #ddd;
            color: #555;
        }

        .table-row {
            border-bottom: 1px dashed #ddd;
            color: #333;
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-row span {
            font-weight: 500;
        }

        .table-row span:nth-child(2) {
            font-weight: 700;
        }

        .box_heading {
            border-bottom: var(--vz-card-header-border-width) solid var(--vz-border-color);
            padding-bottom: 12px;
            margin-bottom: 1.4rem !important;
        }

        .multiplePrices {
            width: 100%;
        }

        .banner-thumb {
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .banner-thumb:hover {
            transform: scale(1.05);
        }

        .modal-content .btn-close {
            background-color: rgba(255, 255, 255, 0.8);
            /* light background behind X */
            border-radius: 50%;
        }

        .event_status {
            font-size: 16px;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            <!-- Page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Event Management</a></li>
                                <li class="breadcrumb-item active">View Event</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    @if ($event->banners->count())
                        <div class="banner text-center"
                            style="background-image: url('{{ asset('storage/' . $event->banners->first()->banner) }}');">
                            <div class="overlay"></div>
                            <h2 class="banner-title">{{ $event->title }}</h2>
                        </div>
                    @else
                        <h2 class="text-center my-4">{{ $event->title }}</h2>
                    @endif
                </div>
            </div>



            <div class="row">
                <div class="col-9">
                    <!-- Event Info -->

                    <div class="row">

                        <div class="col-12">
                            <!-- Multiple prices -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">

                                    <div class="box_heading d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0"><i class="ri-user-line"></i> Booked Tickets</h4>
                                        <span class="d-flex align-items-center">
                                            <i class="ri-ticket-2-line"></i>
                                            Total Booked: {{ $bookedCount }}
                                        </span>
                                    </div>
                                    @if ($bookedCount < 1)
                                        <p>No paid tickets for this event yet.</p>
                                    @else
                                        <table id="eventTicketsTable" class="table table-striped table-bordered table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th></th>
                                                    <th>Ticket Number</th>
                                                    <th class="text-right">Price</th>
                                                    <th>User</th>
                                                    <th>Book From</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="box_heading">
                                <h4 class="mb-0"><i class="ri-file-text-line me-2"></i>Description</h4>
                            </div>

                            <!-- Description -->
                            <div class="">
                                <p>{{ $event->description ?? '-' }}</p>
                            </div>
                        </div>
                    </div>



                    @if ($event->banners->count())
                        <div class="card shadow-sm border-0 mb-4 card-body">
                            <div class="box_heading">
                                <h4 class="mb-0"><i class="ri-gallery-line me-2"></i>Event Banners</h4>
                            </div>
                            <div class="row g-3">
                                @foreach ($event->banners as $index => $banner)
                                    <div class="col-6 col-md-3">
                                        <img src="{{ asset('storage/' . $banner->banner) }}"
                                            class="img-fluid rounded border banner-thumb" data-bs-toggle="modal"
                                            data-bs-target="#bannerModal" data-index="{{ $index }}"
                                            alt="Event Banner">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0 position-relative">

                                    <!-- Close Button -->
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                                        data-bs-dismiss="modal" aria-label="Close"></button>

                                    <!-- Gallery Image -->
                                    <img id="bannerModalImage" src="" class="img-fluid rounded shadow"
                                        alt="Full Banner">

                                    <!-- Navigation Buttons -->
                                    <button type="button" id="prevBanner"
                                        class="btn btn-light position-absolute top-50 start-0 translate-middle-y ms-2">&lt;</button>
                                    <button type="button" id="nextBanner"
                                        class="btn btn-light position-absolute top-50 end-0 translate-middle-y me-2">&gt;</button>
                                </div>
                            </div>
                        </div>
                    @endif



                    <!-- Event Rules -->
                    @if ($event->rules)
                        <div class="card shadow-sm border-0 mb-4 card-body">
                            <div class="box_heading">
                                <h4 class="mb-0"><i class="ri-file-text-line"></i> Event Rules</h4>
                            </div>
                            <div class="">
                                <a href="{{ asset('storage/' . $event->rules) }}" target="_blank"
                                    class="btn btn-outline-primary">
                                    View Document
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Cause -->
                    <div class="card shadow-sm border-0 mb-4 card-body">
                        <div class="box_heading">
                            <h4 class="mb-0"><i class="ri-heart-line me-2"></i>Cause / Beneficiary</h4>
                        </div>
                        <div class="">
                            <p>{{ $event->cause ?? '-' }}</p>
                        </div>
                    </div>



                </div>
                <!-- RIGHT SIDE: Event Details Box -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 evt_sidebar evt_sidebar">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="ri-information-line me-2"></i>Event Details</h4>
                            <div class="event_status">
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $startDate = \Carbon\Carbon::parse($event->start_date);
                                    $endDate = \Carbon\Carbon::parse($event->end_date);

                                    if ($today->between($startDate, $endDate)) {
                                        $eventStatus = 'Active';
                                    } elseif ($today->lt($startDate)) {
                                        $eventStatus = 'Upcoming';
                                    } else {
                                        // $today->gt($endDate)
                                        $eventStatus = 'Past';
                                    }
                                @endphp

                                <span
                                    class="badge
                                            @if ($eventStatus == 'Active') bg-success
                                            @elseif($eventStatus == 'Upcoming') bg-primary
                                            @else bg-danger @endif
                                            text-white">
                                    {{ $eventStatus }}
                                </span>
                            </div>

                        </div>
                        <div class="card-body">

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-medal-2-line"></i>
                                <div>
                                    <small class="label">Winner
                                        {{ $event->is_finalized == true ? 'Announcement' : 'Announced' }} </small><br>
                                    <div class="hstack flex-wrap gap-2 mt-3 mb-3 mb-lg-0">
                                        <button type="button"
                                            class="btn btn-{{ $event->is_finalized ? 'info' : 'success' }} btn-sm btn-load"
                                            onclick="finalizeEvent(this)"
                                            @if ($event->is_finalized == true) disabled @endif)>
                                            {{ $event->is_finalized == true ? 'Finalized' : 'Finalize' }}
                                        </button>

                                        @if ($event->is_finalized)
                                            <a target="_blank" href="{{ route('event.winner', $event->id) }}"
                                                class="btn btn-success btn-sm btn-load">
                                                Winner Screen
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-ticket-line"></i>
                                <div>
                                    <a class="btn btn-info btn-sm btn-load"
                                        href="{{ route('event.webview', $event->id) }}" target="_blank">Raffle
                                        screen</a></small><br>

                                </div>
                            </div>

                            <div class="detail-item-m  align-items-start mt-3">
                                <h5>Ticket prices</h5> <!-- multiplePrices -->
                                <div class="multiplePrices">

                                    <div class="event-prices">
                                        <div class="price-table">
                                            <div class="table-header">
                                                <span><i class="ri-ticket-line"></i> Quantity</span>
                                                <span><i class="ri-money-dollar-circle-line"></i> Price</span>
                                            </div>
                                            @if ($event->multiple_price)
                                                @if ($event->multiplePrices && $event->multiplePrices->count())

                                                    @foreach ($event->multiplePrices as $multiprice)
                                                        <div class="table-row">
                                                            <span> {{ $multiprice->quantity ?? 'Ticket' }}</span>
                                                            <span> {{ format_price(number_format($multiprice->price, 2)) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @else
                                                <div class="table-row">
                                                    <span> 1</span>
                                                    <span>
                                                        {{ format_price(number_format($event->ticket_price, 2)) }}</span>
                                                </div>
                                            @endif

                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-calendar-event-line"></i>
                                <div>
                                    <small class="label">Start Date</small><br>
                                    {{ format_datetime($event->start_date) }}
                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-calendar-check-line"></i>
                                <div>
                                    <small class="label">End Date</small><br>
                                    {{ format_datetime($event->end_date) }}
                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-time-line"></i>
                                <div>
                                    <small class="label">Draw Time</small><br>
                                    {{ format_datetime($event->draw_time) }}

                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-map-pin-line"></i>
                                <div>
                                    <small class="label">Location</small><br>
                                    {{ $event->location }}
                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-user-line"></i>
                                <div>
                                    <small class="label">Max Tickets/User</small><br>
                                    {{ $event->max_tickets_per_user }}
                                </div>
                            </div>

                            <div class="detail-item d-flex align-items-start">
                                <i class="ri-stack-line"></i>
                                <div>
                                    <small class="label">Category</small><br>
                                    {{ $event->category?->name ?? '-' }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const banners = @json($event->banners->map(fn($b) => asset('storage/' . $b->banner)));
            const bannerModal = document.getElementById('bannerModal');
            const bannerModalImage = document.getElementById('bannerModalImage');
            let currentIndex = 0;

            bannerModal.addEventListener('show.bs.modal', function(event) {
                currentIndex = parseInt(event.relatedTarget.getAttribute('data-index'));
                bannerModalImage.src = banners[currentIndex];
            });

            document.getElementById('prevBanner').addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + banners.length) % banners.length;
                bannerModalImage.src = banners[currentIndex];
            });

            document.getElementById('nextBanner').addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % banners.length;
                bannerModalImage.src = banners[currentIndex];
            });
        });
    </script>




@endsection


@push('scripts')
    <script>
        $('#eventTicketsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route(routePrefix() . 'event.tickets.data', $event->id) }}",
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
                    name: 'tickets.created_at',
                    visible: false
                }, {
                    data: 'ticket_number',
                    name: 'tickets.ticket_number'
                },

                {
                    data: 'price',
                    name: 'tickets.price'
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
            ]
        });

        function finalizeEvent(elem) {
            $(elem).prop('disabled', true);
            $(elem).html(`Finalizing...`);
            $.ajax({
                url: "{{ route(routePrefix() . 'event.finalize') }}",
                type: "POST",
                dataType: "JSON",
                data: {
                    "event_id": {{ $event->id }},
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    Swal.fire({
                        title: '🎉 Winner Announced!',
                        text: res.message, // dynamic message from backend
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6',
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(err) {
                    let errObj = err.responseJSON;
                    console.log(errObj.message);
                    Swal.fire({
                        title: '⚠️ Oops!',
                        text: errObj.message || 'Failed to finalize winner. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                    });
                },
                complete: function(jqXHR, textStatus) {
                    console.log("jqXHR", jqXHR, textStatus)

                    if (textStatus === 'success') {
                        $(elem).html(`Finalized`);
                    } else {
                        $(elem).html(`Finalize`);
                        $(elem).prop('disabled', false);
                    }

                }
            })
        }
    </script>
@endpush
