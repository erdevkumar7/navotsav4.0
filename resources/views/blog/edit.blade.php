@extends('layout.main')

@section('title', 'Add User')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Blog Management</a></li>
                                <li class="breadcrumb-item active">Edit Blog</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-8">
                    <form action="{{ route(routePrefix() . 'blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">

        <!-- Title -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                class="form-control @error('title') is-invalid @enderror" placeholder="Enter title">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Banner -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Banner</label>
            <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror">

            @if ($blog->banner)
                <div class="mt-2 d-flex flex-wrap">
                    <div class="position-relative me-2 mb-2 banner-box preview_img" data-id="{{ $blog->id }}">
                        <img src="{{ asset('storage/' . $blog->banner) }}" width="100" height="80"
                            class="rounded border">
                        <button type="button"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-banner-btn"
                            style="padding:2px 6px; font-size:12px;">
                            &times;
                        </button>
                        <input type="hidden" name="keep_media[]" value="{{ $blog->id }}">
                    </div>
                </div>
            @endif

            @error('banner')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <!-- Description -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4"
                class="form-control ckeditor-classic @error('description') is-invalid @enderror"
                placeholder="Description">{!! old('description', $blog->description) !!}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Submit -->
        <div class="col-12 mt-2">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

                </div>
            </div>

        </div>
    </div>
@endsection
    {{-- Delete banner image --}}
    <script>
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-banner-btn")) {
                const box = e.target.closest(".banner-box");
                // Remove the hidden input so backend knows it's deleted
                box.querySelector("input[name='keep_media[]']").remove();
                // Remove preview from UI
                box.remove();
            }
        });
    </script>

@push('scripts')
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <!-- quill js -->
    <script src="{{ asset('assets/libs/quill/quill.min.j') }}s"></script>

    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endpush
