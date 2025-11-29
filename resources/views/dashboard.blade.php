@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">

                    <div class="h-100">
                        <div class="row">
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'buyers') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Total Users</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['user_count'] }}">0</span>
                                                    </h4>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-user-circle text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}

                            {{-- <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'organizers') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Total Organizer</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-danger fs-14 mb-0">
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['organizer_count'] }}">0</span>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-user-circle text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Total Tickets</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_tickets'] }}">0</span>
                                                    </h4> --}}

                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value1"
                                                            data-target="165.89">{{ $ticketCount }}</span>
                                                    </h4>


                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-detail text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Student Pass</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_tickets'] }}">0</span>
                                                    </h4> --}}

                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value1"
                                                            data-target="165.89">{{ $studentTickets }}</span>
                                                    </h4>


                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-detail text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Professional Pass (Adult)</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_tickets'] }}">0</span>
                                                    </h4> --}}

                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value1"
                                                            data-target="165.89">{{ $professionalAdultTickets }}</span>
                                                    </h4>


                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-detail text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Professional Pass (Family of 2)</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_tickets'] }}">0</span>
                                                    </h4> --}}

                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value1"
                                                            data-target="165.89">{{ $professionalFamilyTickets }}</span>
                                                    </h4>


                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-detail text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Host Pass (Family of 4)</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_tickets'] }}">0</span>
                                                    </h4> --}}

                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value1"
                                                            data-target="165.89">{{ $hostFamilyTickets }}</span>
                                                    </h4>


                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-detail text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                                    Revenue</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                {{-- <h5 class="text-muted fs-14 mb-0">
                                                    +0.00 %
                                                </h5> --}}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span
                                                        class="counter-value1"
                                                        data-target="165.89">{{ $admin_details['total_earning'] }}</span>
                                                </h4> --}}

                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">₹<span
                                                        class="counter-value1"
                                                        data-target="165.89">{{ $totalRevenue }}</span>
                                                </h4>


                                                {{-- <a href="#" class="text-decoration-underline">Withdraw money</a> --}}
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                                    <i class="bx bx-wallet text-warning"></i>
                                                </span>
                                            </div>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="{{ route(routePrefix() . 'event.index') }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Total Events</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['total_events'] }}">0</span>
                                                    </h4>
                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-calendar-event text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Active Events</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['active_events'] }}">0</span>
                                                    </h4>
                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-calendar-event text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <!-- card -->
                                <div class="card card-animate">
                                    <a href="">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Past Events</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                                    </h5>
                                                </div>
                                            </div>


                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{ $admin_details['past_events'] }}">0</span>
                                                    </h4>
                                                    {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-calendar-event text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- end card body -->
                                </div><!-- end card -->
                            </div>

                        </div>



                    </div> <!-- end .h-100-->

                </div> <!-- end col -->

            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection
