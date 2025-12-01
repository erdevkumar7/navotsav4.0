@extends('layout.main')

@section('title', 'Edit Ticket')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h3>Edit Ticket</h3>

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

                    <div class="col-md-6 mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control" value="{{ $event->qty }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ $event->amount }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Payment Proof</label>
                        <input type="file" name="payment_image" class="form-control">

                        @if ($event->payment_image)
                            <img src="{{ asset('payment_proofs/' . $event->payment_image) }}" width="120" class="mt-2">
                        @endif
                    </div>

                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.ticket.list') }}" class="btn btn-secondary">Back</a>

            </form>
        </div>
    </div>
@endsection
