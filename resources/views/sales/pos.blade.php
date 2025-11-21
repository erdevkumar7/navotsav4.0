
@extends('layout.main')
@section('title', 'Pos Sales')

@section('content')
   <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
             @include('sales.partials.title',['name'=>'Pos'])

              <!-- Pos Table -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <!--Filter Section-->
                        <div class="card-header d-flex justify-content-between align-items-center">
                             <div class="d-flex gap-2 mt-3 mb-3">
                             </div>
                        </div>

                          <!--Table -->
                        <div class="card-body table-responsive">
                             <table class="table  table-bordered table-striped" id="posSalesTable">
                               <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Created At</th>
                                        <th>User</th>
                                        <th>Event</th>
                                        <th>Amount</th>
                                        <th>Breakdown</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')

<script>
       $(document).ready(function() {

        console.log("asdfasdfas");
         let table = $('#posSalesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route(routePrefix() . 'pos.sales.data') }}",
                        data: function(d) {
                            // d.category_id = $('#filterCategory').val();
                            // d.type = $('#filterType').val();
                            // d.start_date = $('#filterStartDate').val();
                            // d.end_date = $('#filterEndDate').val();
                        }
                    },
                    // ✅ Default sorting by created_at (column index 1)
                    order: [
                        [1, 'desc']
                    ],

                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'user', name: 'user' },
                        { data: 'event', name: 'event' },
                        { data: 'amount', name: 'amount' },
                        {
                            data: 'breakdown',
                            name: 'breakdown',
                            orderable: false,
                            searchable: false
                        },
                    ]

                });


       })
</script>

@endpush

@endsection

