@extends('layout.main')

@section('title', 'Admin Settings')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Add New Setting --}}
            <form action="{{ route('admin.settings.save') }}" method="POST" class="mt-4">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="key" class="form-label">Setting Key</label>
                        <input type="text" name="key" id="key" class="form-control" value="{{ old('key') }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label for="value" class="form-label">Setting Value</label>
                        <input type="text" name="value" id="value" class="form-control" value="{{ old('value') }}"
                            required>
                    </div>
                    <div class="col-md-4 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mt-3">Add Setting</button>
                    </div>
                </div>
            </form>

            <hr>
            <h4>Settings</h4>



            <form action="{{ route('admin.settings.updateAll') }}" method="POST">
                @csrf
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Setting Key</th>
                            <th>Setting Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admin_setting as $setting)
                            <tr>
                                <td>{{ $setting->key }}</td>
                                <td>
                                    <input type="text" name="settings[{{ $setting->id }}]" value="{{ $setting->value }}"
                                        class="form-control">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No settings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($admin_setting->count() > 0)
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save All Changes</button>
                    </div>
                @endif
            </form>



        </div>
    </div>


    {{-- Script Section --}}
    @section('scripts')


    @endsection
@endsection
