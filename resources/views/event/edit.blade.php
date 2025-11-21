@extends('layout.main')

@section('title', 'Edit Event')

@section('content')

    <style>
        .preview_img img {
            object-fit: cover;
            background: #ededed;
        }
    </style>

    @php
       // $isLocked = ($event->sold_tickets >0) || ($event->is_finalized==true);
        $isLocked =$event->is_finalized==true;
    @endphp


    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        {{-- <h4 class="mb-sm-0">Edit Event</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Event Management</a></li>
                                <li class="breadcrumb-item active">Edit Event</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="eventForm" method="POST" enctype="multipart/form-data"
                action="{{ route(routePrefix() . 'event.update', $event->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Event Title -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Event Title *</label>
                            <input type="hidden" name="event_id" value="$event->id">
                            <input type="text" name="title" class="form-control" id="eventTitle"
                                value="{{ old('title', $event->title) }}"
                                {{ $isLocked ? 'disabled' : '' }}>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Event Category -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="eventCategory" class="form-label">Event Category *</label>
                            <select name="category_id" id="eventCategory" class="form-control"  {{ $isLocked ? 'disabled' : '' }}>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
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
                            <label for="eventVisble" class="form-label">Event Visibility *</label>
                            <select name="visiblity" id="eventVisble" class="form-control"  {{ $isLocked ? 'disabled' : '' }}>
                                <option value="">-- Select Visibility --</option>
                                <option value="online"
                                    {{ old('visiblity', $event->visiblity ?? '') == 'online' ? 'selected' : '' }}>Online
                                </option>
                                <option value="offline"
                                    {{ old('visiblity', $event->visiblity ?? '') == 'offline' ? 'selected' : '' }}>Offline
                                    / POS</option>
                                <option value="both"
                                    {{ old('visiblity', $event->visiblity ?? '') == 'both' ? 'selected' : '' }}>Both
                                </option>
                            </select>
                            @error('visiblity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">

                        <!-- Multiple Price Section -->
                        <div class="mb-3" id="multiplePrice">
                            <label class="form-label">Ticket Prices & Quantities *</label>
                            <div id="priceRows">
                                @php
                                    $bookingStarted = $event->tickets->count() > 0;

                                @endphp
                                @if ($event->multiplePrices->count())
                                    @foreach ($event->multiplePrices as $index => $price)
                                        <div class="row price-row mb-2">
                                            <div class="col-5">
                                                <input type="hidden" name="ticket_prices[{{ $index }}][id]" value="{{ $price->id }}">
                                                <input type="number" name="ticket_prices[{{ $index }}][quantity]"
                                                    class="form-control"
                                                    value="{{ old('ticket_prices.' . $index . '.quantity', $price->quantity) }}"
                                                    placeholder="Ticket Quantity">

                                                @error("ticket_prices.$index.quantity")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-5">
                                                <input type="number" name="ticket_prices[{{ $index }}][price]"
                                                    class="form-control"
                                                    value="{{ old('ticket_prices.' . $index . '.price', $price->price) }}"
                                                    placeholder="Ticket Price">
                                                @error("ticket_prices.$index.price")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-2 d-flex">
                                                @if (!$bookingStarted)
                                                    @if ($loop->first)
                                                        <button type="button" class="btn btn-success addRow">+</button>
                                                    @else
                                                        <button type="button" class="btn btn-danger removeRow">-</button>
                                                    @endif
                                                @else
                                                    @if ($loop->first)
                                                        <button type="button" class="btn btn-success addRow">+</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row price-row mb-2">
                                        <div class="col-5">
                                            <input type="number" name="ticket_prices[0][quantity]" class="form-control"
                                                value="{{ old('ticket_prices.0.quantity', 1) }}"
                                                placeholder="Ticket Quantity">
                                            @error('ticket_prices.0.quantity')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-5">
                                            <input type="number" name="ticket_prices[0][price]" class="form-control"
                                                value="{{ old('ticket_prices.0.price') }}" placeholder="Ticket Price">
                                            @error('ticket_prices.0.price')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-2 d-flex">
                                            <button type="button" class="btn btn-success addRow">+</button>
                                        </div>
                                    </div>
                                @endif
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
                                value="{{ old('ticket_quantity', $event->ticket_quantity) }}">
                            @error('ticket_quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div> --}}

                    <!-- Single Ticket Price -->
                    {{-- <div class="col-4" id="singleTicketPrice">
                        <div class="mb-3" id="multiplePrice">

                            <div class="mb-3">
                                <label for="ticketPrice" class="form-label">Ticket Price *</label>
                                <input type="number" name="ticket_price" class="form-control" id="ticketPrice"
                                    placeholder="Ticket Price" value="{{ old('ticket_price', $event->ticket_price) }}">
                                @error('ticket_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div> --}}

                       <!--Winner Type-->
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="winner_type" class="form-label">Winner Type *</label>
                            <select name="winner_type" id="winner_type" class="form-control"  {{ $isLocked ? 'disabled' : '' }} required>
                                <option value="">-- Select Winner Type --</option>
                                <option value="automatic"
                                    {{ old('winner_type', $event->winner_type ?? '') == 'automatic' ? 'selected' : '' }}>
                                    Automatic Announcement</option>
                                <option value="manual"
                                    {{ old('winner_type', $event->winner_type ?? '') == 'manual' ? 'selected' : '' }}>
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
                            <label for="location" class="form-label">Event Location *</label>
                            <input type="text" name="location" id="location" class="form-control" min="1"
                                placeholder="Address, neighborhood, city, or ZIP"
                                value="{{ old('location', optional($event)->location ?? '') }}"
                                {{ $isLocked ? 'disabled' : '' }} required>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="row">

                    @php
                        $today = \Carbon\Carbon::today();
                        $isReadonly = $event->start_date <= $today || $event->end_date <= $today;
                    @endphp

                    <!-- Start Date -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $event->start_date) }}"
                                min="{{ \Carbon\Carbon::today()->toDateString() }}"

                                {{-- @if ($isReadonly) readonly @endif --}}
                                 @if($event->is_publish) readonly @endif

                                >
                            @error('start_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">End Date *</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $event->end_date) }}"
                                min="{{ \Carbon\Carbon::today()->toDateString() }}"
                               @if($event->is_publish) readonly @endif>
                            @error('end_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Draw Time -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">Draw Time *</label>
                            <input type="datetime-local" name="draw_time" class="form-control"
                                value="{{ old('draw_time', $event->draw_time ? \Carbon\Carbon::parse($event->draw_time)->format('Y-m-d\TH:i') : '') }}"
                                min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                 @if($event->is_publish) readonly @endif>
                            @error('draw_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Event Banners -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">Event Banners *</label>
                            <input type="file" name="banners[]" class="form-control"
                                accept=".jpeg,.jpg,.png,.gif,.svg"  {{ $isLocked ? 'disabled' : '' }} multiple>
                            <small id="bannerHelp" class="text-muted">
                                Select one or more banners (Recommended size: 1200x600px).
                            </small>
                            @error('banners')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @error('banners.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($event->banners->count())
                                <div class="mt-2 d-flex flex-wrap">
                                    @foreach ($event->banners as $banner)
                                        <div class="position-relative me-2 mb-2 banner-box preview_img"
                                            data-id="{{ $banner->id }}">
                                            <img src="{{ asset('storage/' . $banner->banner) }}" width="100"
                                                height="80" class="rounded border">
                                            <button type="button"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-banner-btn"
                                                style="padding:2px 6px; font-size:12px;">
                                                &times;
                                            </button>
                                            <input type="hidden" name="keep_media[]" value="{{ $banner->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event Screen -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="banners" class="form-label">Event Screen *</label>
                            <input type="file" name="eventscreen" id="eventscreen" class="form-control" multiple
                                accept=".jpeg,.jpg,.png,.gif,.svg"  {{ $isLocked ? 'disabled' : '' }}>
                            <small id="eventscreenHelp" class="text-muted">
                                Select one or more event screen (Recommended size:875x800px).
                            </small>
                            @error('eventscreen')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($event->event_screen)
                                <div class="mt-2 d-flex flex-wrap">
                                   <div class="position-relative me-2 mb-2 banner-box preview_img"
                                            data-id="{{ $event->id }}">
                                            <img src="{{ asset('storage/' . $event->event_screen) }}" width="100"
                                                height="80" class="rounded border">
                                            <button type="button"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-banner-btn"
                                                style="padding:2px 6px; font-size:12px;">
                                                &times;
                                            </button>
                                            <input type="hidden" name="keep_event_screen[]" value="{{ $event->id }}">
                                        </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event Rules -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">Event Rules</label>
                            <input type="file" name="rules" class="form-control" accept=".pdf,.doc,.docx,.txt"  {{ $isLocked ? 'disabled' : '' }}>
                            @error('rules')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($event->rules)
                                <div class="mt-2">
                                    @php
                                        $ext = pathinfo($event->rules, PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array($ext, ['pdf']))
                                        <i class="ri-file-pdf-line ri-2x text-danger"></i>
                                    @elseif(in_array($ext, ['doc', 'docx']))
                                        <i class="ri-file-word-line ri-2x text-primary"></i>
                                    @else
                                        <i class="ri-file-text-line ri-2x text-secondary"></i>
                                    @endif
                                    <a href="{{ asset('storage/' . $event->rules) }}" target="_blank"
                                        class="d-block mt-1">
                                        View Document
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cause -->
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">fundraiser Details</label>
                            <textarea name="cause" class="form-control"  {{ $isLocked ? 'disabled' : '' }}>{{ old('cause', $event->cause) }}</textarea>
                            @error('cause')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-8">
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control"  {{ $isLocked ? 'disabled' : '' }}>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-lg-12">
                        <div class="text-start">
                            {{-- <button type="submit" name="action" value="save" class="btn btn-primary">Save in
                                Draft</button>
                            &nbsp;&nbsp; --}}
                            <button type="submit" name="action" value="publish"
                                class="btn btn-info mb-5 px-5"  {{ $isLocked ? 'disabled' : '' }}>Save</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>



    {{-- Delete banner image --}}
    {{-- <script>
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-banner-btn")) {
                const box = e.target.closest(".banner-box");
                // Remove the hidden input so backend knows it's deleted
                box.querySelector("input[name='keep_media[]']").remove();
                // Remove preview from UI
                box.remove();
            }
        });
    </script> --}}

    <script>
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-banner-btn")) {

                const box = e.target.closest(".banner-box");

                // Remove keep_media[] if it exists
                let keepBanner = box.querySelector("input[name='keep_media[]']");
                if (keepBanner) keepBanner.remove();

                // Remove keep_event_screen[] if it exists
                let keepScreen = box.querySelector("input[name='keep_event_screen[]']");
                if (keepScreen) keepScreen.remove();

                // Remove preview box
                box.remove();
            }
        });
    </script>





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
            let lastIndex = document.querySelectorAll('#priceRows .price-row').length - 1;
            let rowIndex = lastIndex >= 0 ? lastIndex + 1 : 1;

            // Add Row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('addRow')) {
                    e.preventDefault();

                    let newRow = `
                <div class="row price-row mb-2">
                    <div class="col-5">
                        <input type="number" name="ticket_prices[${rowIndex}][quantity]" class="form-control"
                               placeholder="Ticket Quantity" value="${rowIndex+1}">
                    </div>
                    <div class="col-5">
                        <input type="number" name="ticket_prices[${rowIndex}][price]" class="form-control"
                               placeholder="Ticket Price">
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
