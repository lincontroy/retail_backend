@extends('adminlte::page')

@section('title', 'Shops')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Shops</h1>
        <div>
            <a href="{{ route('admin.shops.users-index') }}" class="btn btn-info mr-2">
                <i class="fas fa-users"></i> View All Assignments
            </a>
            <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Shop
            </a>
        </div>
    </div>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Shops</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search shops...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="50">#</th>
                            <th>Shop Name</th>
                            <th>Code</th>
                            <th>Address</th>
                            <th>Coordinates</th>
                            <th>Routes</th>
                            <th>Users</th>
                            <th width="220" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $shop)
                            <tr>
                                <td><strong>#{{ $shop->id }}</strong></td>
                                <td>
                                    <strong class="d-block">{{ $shop->name }}</strong>
                                    @if($shop->created_at)
                                        <small class="text-muted">Added: {{ $shop->created_at->format('M j, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($shop->code)
                                        <span class="badge badge-info">{{ $shop->code }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shop->address)
                                        <small title="{{ $shop->address }}">{{ Str::limit($shop->address, 30) }}</small>
                                    @else
                                        <span class="text-muted">No address</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shop->latitude && $shop->longitude)
                                        <small class="text-success">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ number_format($shop->latitude, 4) }}, {{ number_format($shop->longitude, 4) }}
                                        </small>
                                    @else
                                        <span class="text-muted">No coordinates</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shop->routes->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($shop->routes->take(3) as $route)
                                                <span class="badge badge-primary" title="{{ $route->name }}">
                                                    {{ Str::limit($route->name, 15) }}
                                                    @if($route->pivot->order)
                                                        <small>(#{{ $route->pivot->order }})</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                            @if($shop->routes->count() > 3)
                                                <span class="badge badge-light" title="View all routes">
                                                    +{{ $shop->routes->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No routes</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shop->users->count() > 0)
                                        <div class="user-list">
                                            @foreach($shop->users->take(3) as $user)
                                                <div class="user-item mb-1">
                                                    <span class="badge badge-success" title="{{ $user->name }} ({{ $user->email }})">
                                                        <i class="fas fa-user"></i>
                                                        {{ Str::limit($user->name, 12) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                            @if($shop->users->count() > 3)
                                                <div class="user-item">
                                                    <span class="badge badge-light" title="View all users">
                                                        +{{ $shop->users->count() - 3 }} more
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                <small class="text-muted">{{ $shop->users->count() }} user(s)</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No users</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.shops.show', $shop) }}" 
                                           class="btn btn-info" 
                                           title="View Details"
                                           data-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.edit', $shop) }}" 
                                           class="btn btn-warning" 
                                           title="Edit Shop"
                                           data-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.manage-routes', $shop) }}" 
                                           class="btn btn-secondary" 
                                           title="Manage Routes"
                                           data-toggle="tooltip">
                                            <i class="fas fa-route"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.manage-users', $shop) }}" 
                                           class="btn btn-light border" 
                                           title="Manage Users"
                                           data-toggle="tooltip">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <form action="{{ route('admin.shops.destroy', $shop) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    title="Delete Shop"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Are you sure you want to delete this shop? This will remove all route and user associations.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-store fa-3x mb-3"></i>
                                        <h4>No Shops Found</h4>
                                        <p>Get started by creating your first shop.</p>
                                        <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Shop
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($shops->hasPages())
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $shops->links() }}
                </div>
                <div class="float-left">
                    <small class="text-muted">
                        Showing {{ $shops->firstItem() ?? 0 }} to {{ $shops->lastItem() ?? 0 }} of {{ $shops->total() }} shops
                    </small>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .badge {
        font-size: 0.75em;
    }
    .user-list {
        max-width: 150px;
    }
    .user-item {
        line-height: 1.2;
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        
        // Search functionality
        $('input[name="table_search"]').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@stop