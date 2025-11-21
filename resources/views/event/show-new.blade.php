@extends('layout.main')

@section('title', 'View Event')

@section('content')


    <!-- Internal CSS -->
    <style>
        .admin_detail_event h1.page-title {
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 30px;
            font-size: 1.8rem;
            text-transform: uppercase;
        }

        .admin_detail_event .event-header-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
            padding: 28px;
            background: var(--surface);
            border: solid 1px #d3d3d3bf;
            border-radius: 10px;
            background: #fff;
            padding-bottom: 0px;
        }

        h5.mb-3.fw-semibold {
            font-size: 1.25rem;
        }

        .admin_detail_event .event-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .admin_detail_event .event-sub {
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 14px;
        }

        .admin_detail_event .badge-status {
            background: #099885;
            color: #fff;
            border-radius: 999px;
            padding: 5px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }



        .admin_detail_event .card-rounded {
            background: var(--surface);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            padding: 24px;
            transition: transform 0.2s ease;
            border: solid 1px #d3d3d3bf;
            border-radius: 10px;
            background: #fff;
            margin-bottom: 15px !important;
        }

        .admin_detail_event .detail-label {
            color: #000;
            font-weight: 500;
            width: 180px;
            flex-shrink: 0;
            font-size: 14px;
        }

        .admin_detail_event .detail-value {
            color: #1f2937;
            font-weight: 400;
            font-size: 14px;
        }

        .duration-start .duration strong {
            font-weight: 400;
            font-size: 15px;
        }

        .admin_detail_event .card-rounded strong {
            font-weight: 600;
        }

        .admin_detail_event .event-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        .admin_detail_event .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #e5e7eb;
            align-items: center;
            font-size: 14px;
        }

        .admin_detail_event .summary-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .admin_detail_event .btn-outline-primary,
        .btn-primary {
            border-radius: 8px;
            font-weight: 500;
            background: #099885;
            color: #fff;
            border: none;
            padding: 11px 0px;
        }

        .action-row a.btn.btn-outline-primary.flex-fill {
            border-radius: 8px;
            font-weight: 500;
            background: #099885;
            color: #fff;
            border: none;
            padding: 11px 0px;
            max-width: 190px;
        }

        .action-row .btn-primary {
            background: #299cdb;
            border-color: #299cdb;
            border: none;
            max-width: 190px;
        }

        .admin_detail_event .btn-primary:hover {
            background: #299cdb;
        }

        .action-row {
            margin-top: 35px;
            gap: 16px;
            justify-content: right;
        }

        .action-row a {
            padding: 11px 0px;
            font-size: 15px;
        }

        a.btn.btn-outline-primary.flex-fill .active {
            background: #405189;
        }

        .event-overview button.btn.btn-outline-primary.btn-sm.w-100 {
            background: #099885;
            width: 80% !important;
            font-size: 14px;
        }

        .duration-start .duration {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .duration-start {
            display: flex;
            gap: 0px;
            justify-content: space-between;
        }

        .status_duration {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            margin-left: 0px !important;
        }

        small.duration-text {
            font-size: 16px;
            color: #000;
            font-weight: 500;
        }

        .inner_draw {
            border-bottom: 1px dashed #e5e7eb;
            margin-bottom: 20px;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        small.status-text {
            font-weight: 500;
        }

        .winners-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
        }

        .special-draw h6 {
            font-size: 1.25rem;
        }

        .winners-details p {
            margin-bottom: 0px;
        }

        .winners-details small {
            color: #000;
            font-size: 14px;
        }

        .winner-name-add {
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 10px;
            padding-top: 10px;
            font-size: 14px;
        }

        .update-btn button {
            width: 40% !important;
            background: #299cdb;
            color: #fff;
            padding: 8px 0px;
        }

        .update-btn button:hover {
            background: #299cdb;
            color: #fff;
        }

        .update-btn {
            margin-top: 45px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        i.bi.bi-check-circle-fill.me-1 {
            color: #099885;
        }

        h6.mb-3.fw-semibold {
            font-size: 1.25rem;
        }

        .page-content.event_add_detail {
            margin-bottom: 35px;
        }

        .card.shadow-sm.border-0.mb-4 {
            border-radius: 10px;
            margin-bottom: 0px !important;
        }

        p.no-paid {
            padding-top: 10px;
        }

        .description-text {
            padding-top: 10px;
        }

        p {
            font-size: 14px;
        }

        .card-body {
            border: solid 1px #d3d3d3bf;
            border-radius: 10px;
        }

        /* ========== Financial Overview Section ========== */
        .financial-overview .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .financial-overview .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* ----- Ticket Prices Table ----- */
        .price-table {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 1rem;
            background-color: #fafafa;
        }

        .table-header,
        .table-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .table-header {
            background-color: #f1f3f5;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-row {
            border-bottom: 1px solid #eaeaea;
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-row span {
            color: #555;
        }

        /* Icons */
        .table-header i {
            color: #0d6efd;
            margin-right: 5px;
        }

        /* ----- Event Details Section ----- */
        .detail-item {
            margin-bottom: 1rem;
        }

        .detail-item i {
            font-size: 1.4rem;
            color: #0d6efd;
            margin-right: 12px;
            background: #e8f0fe;
            border-radius: 8px;
            padding: 6px 10px;
        }

        .detail-item .label {
            font-weight: 600;
            color: #444;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .detail-item div {
            color: #555;
            font-size: 14px;
        }

        /* ----- Left Section (Ticket Prices) ----- */
        .detail-item-m h5 {
            font-weight: 600;
            /* color: #222; */
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .image_add_new_banner img {
            width: 80%;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .card-body.start-date {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            width: 100%;
            padding: 15px;
            align-items: center;
        }

        .financial-overview {
            margin-top: 0px;
            padding: 0;
            width: 100%;
            margin: auto;
        }

        .card {
            height: 100%;
        }

        .start_date_format {
            margin-top: 15px;
        }

        .cause_text {
            padding-top: 10px;
        }

        span.badge.rounded-pill strong {
            padding: 5px 9px;
            line-height: 1.5;
            font-size: 14px;
        }

        .admin_detail_event .box_heading h4 {
            font-weight: 600;
        }

        /* ----- Responsive Design ----- */
        @media (max-width: 767px) {
            .financial-overview .card {
                margin-bottom: 1rem;
            }

            .table-header,
            .table-row {
                font-size: 0.9rem;
                padding: 8px 12px;
            }

            .detail-item i {
                font-size: 1.2rem;
                padding: 6px;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .admin_detail_event h1.page-title {
                font-size: 1.4rem;
            }

            .admin_detail_event .detail-label {
                width: 140px;
            }

            .admin_detail_event .event-image {
                width: 100px;
                height: 100px;
            }

            .admin_detail_event .card-rounded {
                padding: 18px;
            }
        }
    </style>


    <div class="page-content event_add_detail">
        <div class="container-fluid">

            <!-- Page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Event Management</a></li>
                                <li class="breadcrumb-item active">Event Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin_detail_event">

                <!-- Page title -->
                <!-- <h1 class="page-title text-center text-md-start">Past Raffle — Event Details</h1> -->

                <!-- Event Header -->
                <div class="event-header-card mb-4">
                    <div class="event_draw">
                        <div class="inner_draw">
                            <!-- <div class="event-title">Diwali Lucky Draw – Oct 2025</div> -->

                            @if ($event->banners->count())
                                <div class="event-title">{{ $event->title }}</div>
                            @else
                                <!-- <h2 class="text-center my-4">{{ $event->title }}</h2> -->
                                <div class="event-title">{{ $event->title }}</div>
                            @endif

                            <div class="ms-3 status_duration">
                                <small class="status-text">Status</small><br />
                                <span class="badge-status">{{ $event->is_finalized ? 'Past' : 'Active' }}</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Event Overview -->
                <div class="card-rounded mb-4 event-overview">
                    <div class="row gx-4 align-items-center">
                        <div class="col-lg-6">
                            <h5 class="mb-3 fw-semibold">Event Overview</h5>

                            {{-- <div class="d-flex mb-3">
                                <div class="detail-label">Event Type</div>
                                <div class="detail-value">Single Winner</div>
                            </div> --}}

                            <div class="d-flex mb-3">
                                <div class="detail-label">Created By</div>
                                <div class="detail-value">{{ $event?->createdBy->name }}
                                    &nbsp;<small>{{ format_datetime($event->created_at) }}</small></div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="detail-label">Draw Method</div>
                                <div class="detail-value">
                                    {{ $event->winner_type == 'manual' ? 'Manual Randomizer' : 'Automatic Randomizer' }}

                                </div>
                            </div>

                            {{-- <div class="d-flex mb-3">
                                <div class="detail-label">Execution Time</div>
                                <div class="detail-value">Oct 7, 2025 — 6:00 PM</div>
                            </div> --}}

                            <div class="d-flex mb-0">
                                <div class="detail-label">Fundrising Details</div>
                                <p>{{ $event->cause ?? '-' }}</p>
                            </div>
                        </div>


                        <div class="col-lg-3 text-center mt-3 mt-lg-0">
                            {{-- @foreach ($event->banners as $index => $banner) --}}
                            <div class="image_add_new_banner">
                                @if ($event->banners && $event->banners->first())
                                    <img src="{{ asset('storage/' . $event->banners->first()->banner) }}"
                                        alt="Event Banner">
                                @else
                                    <img src="{{ asset('images/default-banner.png') }}" alt="No Banner Available">
                                @endif
                            </div>

                            {{-- @endforeach --}}

                            <button class="btn btn-outline-primary btn-sm w-100">Event Banner</button>
                        </div>

                        <div class="col-lg-3 text-center mt-3 mt-lg-0">
                            {{-- @foreach ($event->banners as $index => $banner) --}}
                            <div class="image_add_new_banner">
                                @php
                                    if ($event->event_screen) {
                                        // If event_screen exists, show it
                                        $imgScreen = asset('storage/' . $event->event_screen);
                                    } elseif ($event->banners && $event->banners->first()) {
                                        // Otherwise, use the first banner if available
                                        $imgScreen = asset('storage/' . $event->banners->first()->banner);
                                    } else {
                                        // Fallback image if neither exists
                                        $imgScreen = asset('images/default-banner.png');
                                    }
                                @endphp

                                <img src="{{ $imgScreen }}" alt="Event Image">
                            </div>


                            <form id="bannerForm" action="{{ route(routePrefix() . 'event.screen') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <input type="file" name="screen" id="screenInput" accept="image/*" hidden>
                                <input type="hidden" name="event_id" value="{{ $event->id }}" />
                                <button type="button" id="uploadScreen" class="btn btn-outline-primary btn-sm w-100">
                                    Screen (875x800) <i class="mdi mdi-upload"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Summary section -->
                <div class="row g-3 mb-3 special-draw">
                    <div class="col-lg-6 special-draw-left">
                        <div class="card-rounded summary-card h-100">
                            <h6 class="mb-3 fw-semibold">Participation Summary</h6>

                            <div class="summary-item"><span>Total Tickets
                                    Issued</span><strong>{{ $event->totalSold() }}</strong></div>
                            <div class="summary-item"><span>Total
                                    Participants</span><strong>{{ $totalParticipats }}</strong></div>

                            <div class="summary-item"><span>Cash
                                    Revenue</span><strong>{{ format_price($cashRevenue) }}</strong></div>

                            <div class="summary-item"><span>Online
                                    Revenue</span><strong>{{ format_price($onlineRevenue) }}</strong></div>

                            <div class="summary-item"><span>Total
                                    Revenue</span><strong>{{ format_price($event->totalRevenue()) }}</strong></div>
                            {{-- <div class="summary-item"><span>Free Entries</span><strong>300</strong></div> --}}
                        </div>
                    </div>

                    <div class="col-lg-6 special-draw-right">
                        <div class="card-rounded h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="mb-3 fw-semibold">Winners &amp; Prizes</h6>



                                @if ($event->is_finalized)
                                    @php
                                        $winner = DB::table('raffle_winners')
                                            ->select('raffle_winners.*', 'users.name as winner_name')
                                            ->join('users', 'users.id', '=', 'raffle_winners.user_id')
                                            ->where('event_id', $event->id)
                                            ->first();

                                    @endphp

                                    <div class="mb-3">
                                        {{-- <div class="d-flex justify-content-between mb-2 winner-name-add">
                                            <span class="winner-text-name">Winner's</span>
                                            <span class="text-optioned">Optioned</span>
                                        </div> --}}


                                        <div class="winners-prizes">
                                            <div class="summary-item"><span>{{ $winner->winner_name ?? 'N/A' }}
                                                </span><strong>#{{ $winner->ticket_number ?? 'N/A' }}</strong></div>

                                            <div class="summary-item"><span>Winning Amount Status
                                                </span>
                                                <span
                                                    class="badge rounded-pill bg-info"><strong>{{ $event->prize_settled ? 'PAID' : 'DUE' }}</strong></span>
                                            </div>

                                            <div class="summary-item"><span>Claim Requested
                                                </span>
                                                @php
                                                    $isClaim = 'Not yet';
                                                    if ($claimRow && $claimRow?->status == 'pending') {
                                                        $isClaim = 'Yes';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge rounded-pill bg-primary"><strong>{{ $isClaim }}</strong></span>
                                            </div>


                                            <div class="update-btn">
                                                <button class="btn">Upload Winner Form</button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="height:22px;">Winner is not announced yet</div>
                                @endif

                            </div>


                            <div class="mt-3">

                                @if ($event->is_finalized)
                                    <a target="_blank" href="{{ route('event.winner', $event->id) }}"
                                        class="btn btn-success btn-sm btn-load">
                                        Winner Screen
                                    </a>
                                @else
                                    <button type="button" style=""
                                        class="btn btn-{{ $event->is_finalized ? 'info' : 'success' }} btn-sm btn-load"
                                        onclick="finalizeEvent(this)" @if ($event->is_finalized == true) disabled @endif)>
                                        {{ $event->is_finalized == true ? 'Finalized' : 'Finalize' }}
                                    </button>
                                @endif

                                <a class="btn btn-info btn-sm btn-load" style="margin-left: 10px;"
                                    href="{{ route('event.webview', $event->id) }}" target="_blank">Raffle
                                    screen</a></small>

                            </div>



                        </div>
                    </div>
                </div>


                <div class="row g-3 mb-3 special-draw">

                    <div class="col-lg-6 special-draw-right">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="box_heading">
                                        <h4 class="mb-0"><i class="ri-heart-line me-2"></i>Price Package</h4>
                                    </div>
                                    <div class="cause_text">
                                        <div class="summary-item"><span class="gross-text"><strong>Ticket Qty (Per
                                                    Package).</strong>
                                            </span><strong>Price</strong></div>
                                        @foreach ($event->multiplePrices as $package)
                                            <div class="summary-item"><span class="gross-text">{{ $package->quantity }}
                                                </span><strong>
                                                    {{ format_price($package->price) }}</strong></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 special-draw-right">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="box_heading">
                                        <h4 class="mb-0">Cause / Beneficiary</h4>
                                    </div>
                                    <div class="cause_text">
                                        <p>{{ $event->cause ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Financial Overview -->
                    <div class="row g-3 financial-overview">
                        <div class="col-lg-6">
                            <div class="card-rounded h-100">
                                <h6 class="mb-3 fw-semibold">Financial Overview</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap table-striped-columns mb-0">
                                        <thead>
                                            <tr>
                                                <th>Description</th>

                                                <th>Price</th>
                                                <th>Sold</th>
                                                <th>Total</th>
                                                <th>Tickets</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packageRevenue as $revenue)
                                                <tr>
                                                    <td>{{ $revenue->package_quantity }}-Ticket Combo</td>

                                                    <td>{{ format_price($revenue->package_price) }}</td>
                                                    <td>{{ $revenue->sold_packages }}</td>

                                                    <td>{{ format_price($revenue->package_revenue) }}</td>
                                                    <td>{{ $revenue->sold_tickets }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td>{{ format_price($event->totalRevenue()) }}</td>
                                                <td>{{ $event->totalSold() }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-6 start_date_format">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body start-date">
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




                    <!-- Actions -->
                    <div class="d-flex flex-column flex-md-row action-row">
                        <a target="_blank" href="{{ route(routePrefix() . 'ticket.list', ['event_id' => $event->id]) }}"
                            class="btn btn-primary flex-fill">View All Tickets</a>
                        <a href="#" class="btn btn-outline-primary flex-fill">Back to All Events</a>
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


            document.getElementById("uploadScreen").addEventListener("click", function() {
                document.getElementById("screenInput").click();
            });

            document.getElementById("screenInput").addEventListener("change", function() {
                document.getElementById("bannerForm").submit();
            });
        </script>




    @endsection


    @push('scripts')
        <script>
            $('#eventTicketsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(routePrefix() . 'event.tickets.data', $event->id) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'users.name'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        orderable: false,
                        searchable: false,
                        className: "text-right"
                    }
                ]
            });

            function finalizeEvent(elem) {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Want to end the raffle?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {

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
                                    text: errObj.message ||
                                        'Failed to finalize winner. Please try again.',
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
                });





            }
        </script>
    @endpush
