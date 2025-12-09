@extends('layout.main')

@section('title', 'Donation')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">New Registration</a></li>
                                <li class="breadcrumb-item active">Registration</li>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($events as $key => $event)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $event->name }}</td>
                                            <td>{{ $event->emailid }}</td>
                                            <td>{{ $event->phone }}</td>
                                            <td>{{ $event->amount }}</td>
                                            <td>{{ $event->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#newEventTable').DataTable({
                pageLength: 50
            });
        });
    </script>
@endpush
