@extends('layout.main')

@section('title', 'Role Management')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4>Roles</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route(routePrefix().'roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit
                                    Permissions</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
