@extends('adminlte::page')

@section('title', 'Shops')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Shops</h1>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-success">Add Shop</a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Shop Name</th>
                    <th>Code</th>
                    <th>Address</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Route</th>
                    <th class="text-center">Actions</th>
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
                        <td class="text-center">
                            <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No shops found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $shops->links() }}
    </div>
@stop
