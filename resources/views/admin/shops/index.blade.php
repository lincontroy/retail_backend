@extends('adminlte::page')

@section('title', 'Shops')

@section('content_header')
    <h1>Shops</h1>
    <a href="{{ route('admin.shops.create') }}" class="btn btn-success">Add Shop</a>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Shop Name</th>
                <th>Code</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Route</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shops as $shop)
                <tr>
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->code }}</td>
                    <td>{{ $shop->address }}</td>
                    <td>{{ $shop->latitude }}</td>
                    <td>{{ $shop->longitude }}</td>
                    <td>{{ $shop->route->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No shops found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $shops->links() }}
    </div>
@stop
