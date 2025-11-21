@extends('layout.main')

@section('title', 'Category Creation')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Category Management</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">

                    <form action="{{ route(routePrefix().'category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="eventTitle" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Event Category"
                                        id="eventTitle" value="{{ old('name') }}">
                                    @error('name')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                            </div><!--end col-->


                            <div class="col-lg-12">
                                <div class="text-start">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>

                </div>

            </div>

        </div>
        <!-- container-fluid -->
    </div>

@endsection
