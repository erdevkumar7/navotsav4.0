@extends('layout.main')

@section('title', 'Ticket Packages')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-3">Ticket Packages</h4>
            <a href="{{ route(routePrefix().'ticketpackage.create') }}" class="btn btn-success mb-3">+ Add Package</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Package Name</th>
                        <th>Price & Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $package->name }}</td>
                            <td>
                                @if ($package->items->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($package->items as $item)
                                            <li>
                                                <strong>${{ number_format($item->price, 2) }}</strong>
                                                ({{ $item->ticket_quantity }} tickets)
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No items</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route(routePrefix().'ticketpackage.edit', $package->id) }}" class="btn btn-warning btn-sm"><i
                                        class="ri-pencil-line"></i></a>
                                <form action="{{ route(routePrefix().'ticketpackage.destroy', $package->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')"> <i
                                            class="ri-delete-bin-2-line"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No packages found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
@endsection
