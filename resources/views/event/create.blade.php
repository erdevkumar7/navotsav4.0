@extends('layout.main')

@section('title', 'Event Creation')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        {{-- <h4 class="mb-sm-0">Event Creation</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Event Management</a></li>
                                <li class="breadcrumb-item active">Create Event</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">

                    <form action="{{ route(routePrefix() . 'event.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Event Title -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="eventTitle" class="form-label">Event Title * </label>
                                    <input type="text" name="title" class="form-control" id="eventTitle"
                                        placeholder="Event Title" value="{{ old('title') }}">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Category -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="eventCategory" class="form-label">Event Category *</label>
                                    <select name="category_id" id="eventCategory" class="form-control">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Visiblity -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="eventVisble" class="form-label">Event Visiblity *</label>
                                    <select name="visiblity" id="eventVisble" class="form-control">
                                        <option value="">-- Select Visiblity --</option>
                                        <option value="online" @selected(old('visiblity', 'online') == 'online')>Online</option>
                                        <option value="offline" @selected(old('visiblity', 'online') == 'offline')>POS</option>
                                        <option value="both" @selected(old('visiblity', 'online') == 'both')>Both</option>

                                    </select>
                                    @error('visiblity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                {{-- <div class="mb-3">

                                    <!-- Checkbox below the label -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allowMultiplePrice"
                                            name="multiple_price" value="1" @checked(old('multiple_price'))>
                                        <label class="form-check-label" for="allowMultiplePrice">
                                            <strong>Enable Multiple Prices</strong>
                                        </label>
                                    </div>
                                </div> --}}

                                <!-- Multiple Price Section -->
                                <div class="mb-3" id="multiplePrice">
                                    <label class="form-label">Ticket Prices & Quantities</label>
                                    <div id="priceRows">
                                        <div class="row price-row mb-2">
                                            <div class="col-5">
                                                <input type="number" name="ticket_prices[0][quantity]" class="form-control"
                                                    placeholder="Ticket Quantity" value="{{ old('ticket_prices.0.quantity') }}">
                                                @error('ticket_prices.0.quantity')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-5">
                                                <input type="number" name="ticket_prices[0][price]" class="form-control"
                                                    placeholder="Package Price"value="{{ old('ticket_prices.0.price') }}">
                                                @error('ticket_prices.0.price')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-2 d-flex">
                                                <button type="button" class="btn btn-success addRow">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">

                            <!--  Ticket Quantity -->
                            {{-- <div class="col-4">
                                <div class="mb-3">
                                    <label for="ticket_quantity" class="form-label">Total Ticket Quantity *</label>
                                    <input type="number" name="ticket_quantity" id="ticket_quantity" class="form-control"
                                        min="1" placeholder="Max ticket limit/user"
                                        value="{{ old('ticket_quantity', 10) }}">
                                    @error('ticket_quantity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div> --}}

                            <!--Winner Type-->
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="winner_type" class="form-label">Winner Type *</label>
                                    <select name="winner_type" id="winner_type" class="form-control" required>
                                        <option value="">-- Select Winner Type --</option>
                                        <option value="automatic"
                                             @selected(old('winner_type', 'manual') == 'automatic')>
                                            Automatic Announcement</option>
                                        <option value="manual"
                                        @selected(old('winner_type', 'manual') == 'manual')>
                                            Manual Announcement</option>
                                    </select>
                                    @error('winner_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <!-- Address -->
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Event Location *</label>
                                    <input type="text" name="location" id="address" class="form-control" min="1"
                                        placeholder="Address, neighborhood, city, or ZIP"
                                        value="{{ old('location', '') }}">
                                    @error('location')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>



                        </div>


                        <div class="row">

                            <!-- Start Date -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Start Date *</label>
                                    <input type="date" name="start_date" id="startDate" class="form-control"
                                        value="{{ old('start_date', \Carbon\Carbon::today()->toDateString()) }}"
                                        min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">End Date *</label>
                                    <input type="date" name="end_date" id="endDate" class="form-control"
                                        value="{{ old('end_date') }}"
                                        min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Draw Time -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="drawTime" class="form-label">Draw Time *</label>
                                    <input type="datetime-local" name="draw_time" id="drawTime" class="form-control"
                                        value="{{ old('draw_time') }}"
                                        min="{{ \Carbon\Carbon::today()->format('Y-m-d\TH:i') }}">
                                    @error('draw_time')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Banner -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="banners" class="form-label">Event Banners *</label>
                                    <input type="file" name="banners[]" id="banners" class="form-control" multiple
                                        accept=".jpeg,.jpg,.png,.gif,.svg">
                                    <small id="bannerHelp" class="text-muted">
                                        Select one or more banners (Recommended size: 1200x600px).
                                    </small>
                                    @error('banners')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @error('banners.*')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                             <!-- Event Screen -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="banners" class="form-label">Event Screen *</label>
                                    <input type="file" name="eventscreen" id="eventscreen" class="form-control" multiple
                                        accept=".jpeg,.jpg,.png,.gif,.svg">
                                    <small id="eventscreenHelp" class="text-muted">
                                        Select one or more event screen (Recommended size:875x800px).
                                    </small>
                                    @error('eventscreen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Rules -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rules" class="form-label">Event Rules</label>
                                    <input type="file" name="rules" id="rules" class="form-control"
                                        accept=".pdf,.doc,.docx,.txt">
                                    @error('rules')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cause -->
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="cause" class="form-label">fundraiser</label>
                                    <textarea name="cause" id="cause" class="form-control">{{ old('cause') }}</textarea>
                                    @error('cause')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-8">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-lg-12">
                                <div class="text-start">
                                    <button type="submit" name="action" value="save"
                                        class="btn btn-primary">Save</button>
                                    &nbsp;&nbsp;
                                    <button type="submit" name="action" value="publish" class="btn btn-info">Save &
                                        Publish</button>
                                </div>
                            </div>

                        </div>
                    </form>


                </div>

            </div>

        </div>
        <!-- container-fluid -->
    </div>



    <!-- Ticket price package  -->
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     $("input[name='ticket_prices[0][quantity]']").prop("readonly", true);
        //     const checkbox = document.getElementById("allowMultiplePrice");
        //     //const priceInput = document.getElementById("ticketPrice").closest(".col-4");
        //     const multiplePrice = document.getElementById("multiplePrice");
        //     const singleTicketPrice = document.getElementById("singleTicketPrice")

        //     function toggleFields() {
        //         if (checkbox.checked) {
        //             //    priceInput.style.display = "none";
        //             multiplePrice.style.display = "block";
        //             singleTicketPrice.style.display = "none";
        //         } else {
        //             //   priceInput.style.display = "block";
        //             multiplePrice.style.display = "none";
        //             singleTicketPrice.style.display = "block";
        //         }
        //     }

        //     // Run on load
        //     toggleFields();

        //     // Run on change
        //     checkbox.addEventListener("change", toggleFields);
        // });




        // Create multiple row when i click + icon.
        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = 1;

            // Add Row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('addRow')) {
                    e.preventDefault();

                    let newRow = `
                <div class="row price-row mb-2">
                    <div class="col-5">
                        <input type="number" name="ticket_prices[${rowIndex}][quantity]" class="form-control"
                               placeholder="Ticket Quantity" value="">
                    </div>
                    <div class="col-5">
                        <input type="number" name="ticket_prices[${rowIndex}][price]" class="form-control"
                               placeholder="Package Price">
                    </div>
                    <div class="col-2 d-flex">
                        <button type="button" class="btn btn-danger removeRow">-</button>
                    </div>
                </div>
            `;

                    document.querySelector('#priceRows').insertAdjacentHTML('beforeend', newRow);
                    rowIndex++;
                }
            });

            // Remove Row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeRow')) {
                    e.preventDefault();
                    e.target.closest('.price-row').remove();
                }
            });
        });
    </script>





@endsection
