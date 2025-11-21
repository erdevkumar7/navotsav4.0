@extends('layout.main')

@section('title', 'Edit Ticket Package')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-3">Edit Ticket Package</h4>

            <form action="{{ route(routePrefix().'ticketpackage.update', $package->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Package Name -->
                <div class="row g-2 mb-2">
                    <div class="col-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name', $package->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Price + Ticket Quantity Rows -->
                <div id="package-items">
                    @forelse(old('items', $package->items) as $i => $item)
                        <div class="row g-2 mb-2 package-item">
                            <div class="col-4">
                                <input type="number" name="items[{{ $i }}][ticket_quantity]"
                                    class="form-control" placeholder="Ticket Quantity"
                                    value="{{ old("items.$i.ticket_quantity", $item->ticket_quantity ?? '') }}">
                                @error("items.$i.ticket_quantity")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-4">
                                <input type="number" step="0.01" name="items[{{ $i }}][price]"
                                    class="form-control" placeholder="Price ($)"
                                    value="{{ old("items.$i.price", $item->price ?? '') }}">
                                @error("items.$i.price")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                @if ($i == 0)
                                    <button type="button" class="btn btn-success add-item">+</button>
                                @else
                                    <button type="button" class="btn btn-danger remove-item">-</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="row g-2 mb-2 package-item">
                            <div class="col-4">
                                <input type="number" name="items[0][ticket_quantity]" class="form-control"
                                    placeholder="Ticket Quantity">
                                @error('items.0.ticket_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-4">
                                <input type="number" step="0.01" name="items[0][price]" class="form-control"
                                    placeholder="Price ($)">
                                @error('items.0.price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <button type="button" class="btn btn-success add-item">+</button>
                            </div>
                        </div>
                    @endforelse
                </div>


                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Update Package</button>
                <a href="{{ route(routePrefix().'ticketpackage.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

            <!-- JS for Add/Remove Rows -->
            <script>
                let itemIndex = {{ count(old('items', $package->items)) }};
                document.addEventListener("click", function(e) {
                    if (e.target.classList.contains("add-item")) {
                        e.preventDefault();
                        let newRow = `
                <div class="row g-2 mb-2 package-item">
                    <div class="col-4">
                        <input type="number" name="items[${itemIndex}][ticket_quantity]" class="form-control"
                               placeholder="Ticket Quantity">
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.01" name="items[${itemIndex}][price]" class="form-control"
                               placeholder="Price ($)">

                    </div>
                    <div class="col-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-item">-</button>
                    </div>
                </div>`;
                        document.querySelector("#package-items").insertAdjacentHTML("beforeend", newRow);
                        itemIndex++;
                    }

                    if (e.target.classList.contains("remove-item")) {
                        e.preventDefault();
                        e.target.closest(".package-item").remove();
                    }
                });
            </script>


        </div>
    </div>
@endsection
