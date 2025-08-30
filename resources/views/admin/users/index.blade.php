@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>User Management</h1>
@stop

@section('content')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add User</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Mobile</th><th>Email</th><th>Roles</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->mobile }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete user?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop
