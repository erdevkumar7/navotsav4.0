@extends('layout.main')

@section('title', 'Edit Ticket')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h3>Edit Ticket</h3>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $passNames = [
                    1 => 'Students pass (for 1)',
                    2 => 'Professional pass for adult (for 1)',
                    3 => 'Professional pass for family (family of 2)',
                    4 => 'Host pass only for family (family of 4)',
                ];
            @endphp


            <form action="{{ route('admin.ticket.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>User Name</label>
                        <input type="text" name="user_name" class="form-control" value="{{ $event->user_name }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $event->email }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="{{ $event->mobile }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>JNV</label>
                        <input type="text" name="jnv" class="form-control" value="{{ $event->jnv }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Passout Year</label>
                        <input type="text" name="year" class="form-control" value="{{ $event->year }}" required>
                    </div>

                    {{-- <div class="col-md-6 mb-3">
                        <label>Pass Name</label>
                        <p><strong>Pass Name:</strong> {{ $passNames[$event->pass_id] }}</p>
                    </div> --}}

                    <div class="col-md-6 mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control" value="{{ $event->qty }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ $event->amount }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Payment Transaction ID</label>
                        <input type="text" name="merchant_transaction_id" class="form-control"
                            value="{{ $event->merchant_transaction_id }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-control form-select" disabled>
                            <option value="pending" style="background: #ffc107"
                                {{ $event->payment_status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="success" style="background: #28a745"
                                {{ $event->payment_status == 'success' ? 'selected' : '' }}>
                                Success
                            </option>
                        </select>
                    </div>


                    {{-- <div class="col-md-6 mb-3">
                        <label>Payment Proof</label>
                        <input type="file" name="payment_image" class="form-control">

                        @if ($event->payment_image)
                            <img src="{{ asset('payment_proofs/' . $event->payment_image) }}" width="120"
                                class="mt-2">
                        @endif
                    </div> --}}

                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.ticket.list') }}" class="btn btn-secondary">Back</a>

            </form>
        </div>
    </div>
@endsection
