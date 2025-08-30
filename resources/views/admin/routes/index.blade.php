@extends('adminlte::page')

@section('title', 'Routes')

@section('content_header')
    <h1>Routes</h1>
@stop

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Routes</h2>
    <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">Add Route</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Notes</th>
                <th>Shops Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($routes as $route)
            <tr>
                <td>{{ $route->id }}</td>
                <td>{{ $route->name }}</td>
                <td>{{ $route->notes }}</td>
                <td>{{ $route->shops_count }}</td>
                <td>
                    <a href="{{ route('admin.routes.edit',$route) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.routes.destroy',$route) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">No routes found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $routes->links() }}
@stop
