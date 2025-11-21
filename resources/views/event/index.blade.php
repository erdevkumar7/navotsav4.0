@extends('layout.main')

@section('title', 'Event Lists')




@section('content')

    <style>
        .noEvtdiv {
            padding: 50px;
        }

        .noEvtdiv span {
            font-size: 20px;
        }

        div#eventTable_filter {
            text-align: end;
        }

        #eventTable_filter label {
            text-align: left;
        }

        select#filterCategory {
            min-width: 150px;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        {{-- <h4 class="mb-sm-0">Events</h4> --}}
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Event Management</a></li>
                                <li class="breadcrumb-item active">Events</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Events Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2 mt-3 mb-3">
                                <!-- Category Filter -->
                                <select id="filterCategory" class="form-control form-control-md">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <!-- Event Type Filter -->
                                <select id="filterType" class="form-control form-control-md">
                                    <option value="">All Events</option>
                                    <option value="active">Active</option>
                                    {{-- <option value="upcoming">Upcoming</option> --}}
                                    <option value="past">Past</option>
                                </select>

                                <!-- Date Filters -->
                                <input type="date" id="filterStartDate" class="form-control form-control-md">
                                <input type="date" id="filterEndDate" class="form-control form-control-md">

                                <!-- Buttons -->
                                <button id="filterBtn" class="btn btn-primary btn-md">Apply</button>
                                <button id="resetBtn" class="btn btn-secondary btn-md">Reset</button>
                            </div>


                            <div>
                                @can('create events')
                                    <a href="{{ route(routePrefix() . 'event.create') }}" class="btn btn-success">
                                        <i class="ri-add-line align-bottom me-1"></i> Create Event
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <!-- Table -->
                            <table class="table  table-bordered table-striped" id="eventTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Created At</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Sold Tickets</th>
                                        <th>Total Revenue</th>
                                        <th>Start Date</th>
                                        <th>Draw Time</th>
                                        <th>Created by</th>
                                        <th>Event Visiblity</th>
                                        <th width=100>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>

                        </div><!-- end card-body -->

                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->

        </div>
    </div>


    {{-- datatable code --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#eventTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route(routePrefix() . 'events.data') }}",
                        data: function(d) {
                            d.category_id = $('#filterCategory').val();
                            d.type = $('#filterType').val();
                            d.start_date = $('#filterStartDate').val();
                            d.end_date = $('#filterEndDate').val();
                        }
                    },
                    // ✅ Default sorting by created_at (column index 1)
                    order: [
                        [1, 'desc']
                    ],

                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'events.created_at',
                            visible: false
                        }, {
                            data: 'title',
                            name: 'events.title'
                        },
                        {
                            data: 'category_name',
                            name: 'event_categories.name'
                        },

                        {
                            data: 'event_sold_tickets',
                            name: 'event_sold_tickets',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'event_total_revenue',
                            name: 'event_total_revenue',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'start_date',
                            name: 'events.start_date'
                        },
                        {
                            data: 'draw_time',
                            name: 'events.draw_time'
                        },
                        {
                            data: 'created_by_name',
                            name: 'users.name'
                        },
                        {
                            data: 'visiblity', // if your backend sends this
                            name: 'events.visiblity'
                        },
                        {
                            data: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                // Apply filter
                $('#filterBtn').click(function() {
                    table.draw();
                });

                // Reset filter
                $('#resetBtn').click(function() {
                    $('#filterCategory').val('');
                    $('#filterType').val('');
                    $('#filterStartDate').val('');
                    $('#filterEndDate').val('');
                    table.draw();
                });




                // Delete user handler
                $('#eventTable').on('click', '.delete-this', function(e) {
                    e.preventDefault();
                    const button = $(this);
                    const url = button.data('url');
                    const row = table.row(button.closest('tr')); // now works ✅

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will permanently delete the event!",
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
                                success: function(res) {
                                    if (res.success || res.status) {
                                        row.remove().draw(false);
                                        Swal.fire('Deleted!', res.message ||
                                            'Event has been deleted.', 'success');
                                    } else {
                                        Swal.fire('Error!', res.message ||
                                            'Something went wrong.', 'error');
                                    }
                                },
                                error: function() {
                                    Swal.fire('Error!', 'Unable to delete event.', 'error');
                                }
                            });
                        }
                    });
                });



            });
        </script>



        <script>
            // Change Is publish status
            $(document).on("change", ".change-status", function() {

                let eventId = $(this).data("id");
                let status = $(this).val();

                fetch("{{ route(routePrefix() . 'event.changeStatus') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: eventId,
                            is_publish: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.popup) {
                    // 👇 Show warning popup if details missing
                    toastr.warning(data.message);

                    // Optionally, revert the dropdown back to "Draft"
                    $(this).val("false");
                }
                else if (data.success) {
                    toastr.success(data.message);
                }
                else {
                    toastr.error("Something went wrong. Please try again.");
                }

                    })
                    .catch(error => console.error("Error:", error));
            });
        </script>


        {{-- <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
    @endpush






@endsection
