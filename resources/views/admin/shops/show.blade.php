@extends('adminlte::page')

@section('title', 'Shop Details')

@section('content_header')
    <h1>Shop Details: {{ $shop->name }}</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $shop->name }}</p>
                    <p><strong>Address:</strong> {{ $shop->address ?? 'N/A' }}</p>
                    <p><strong>Coordinates:</strong> 
                        @if($shop->latitude && $shop->longitude)
                            {{ $shop->latitude }}, {{ $shop->longitude }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.shops.edit', $shop) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Shop
                    </a>
                    <a href="{{ route('admin.shops.manage-routes', $shop) }}" class="btn btn-info">
                        <i class="fas fa-route"></i> Manage Routes
                    </a>
                    <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete Shop
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Assigned Routes</h3>
        </div>
        <div class="card-body">
            @if($shop->routes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Order</th>
                                <th>Arrival Time</th>
                                <th>Departure Time</th>
                                <th>Duration</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shop->routes as $route)
                                <tr>
                                    <td>{{ $route->name }}</td>
                                    <td>{{ $route->pivot->order ?? 'N/A' }}</td>
                                    <td>{{ $route->pivot->estimated_arrival ?? 'N/A' }}</td>
                                    <td>{{ $route->pivot->estimated_departure ?? 'N/A' }}</td>
                                    <td>{{ $route->pivot->duration_minutes ? $route->pivot->duration_minutes . ' min' : 'N/A' }}</td>
                                    <td>{{ $route->pivot->notes ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Assigned Users</h3>
                        <a href="{{ route('admin.shops.manage-users', $shop) }}" class="btn btn-sm btn-info float-right">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                    <div class="card-body">
                        @if($shop->users->count() > 0)
                            <div class="row">
                                @foreach($shop->users as $user)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                                         style="width: 40px; height: 40px; font-size: 14px; color: white;">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No users assigned to this shop.</p>
                            <a href="{{ route('admin.shops.manage-users', $shop) }}" class="btn btn-info">
                                <i class="fas fa-user-plus"></i> Assign Users
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-muted">No routes assigned to this shop.</p>
                <a href="{{ route('admin.shops.manage-routes', $shop) }}" class="btn btn-info">
                    <i class="fas fa-route"></i> Assign Routes
                </a>
            @endif
        </div>
    </div>
@stop