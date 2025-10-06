@extends('adminlte::page')

@section('title', 'Routes Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Routes Management</h1>
        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Route
        </a>
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

    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search routes...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="50">ID</th>
                            <th>Route Name</th>
                            <th>User</th>
                            <th>Day</th>
                            <th>Area</th>
                            <th width="80">Priority</th>
                            <th width="100">Duration</th>
                            <th width="100">Status</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                            <tr>
                                <td><strong>#{{ $route->id }}</strong></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold">{{ Str::limit($route->name, 25) }}</span>
                                        @if($route->start_time && $route->end_time)
                                            <small class="text-muted">
                                                <i class="far fa-clock"></i>
                                                {{ $route->start_time->format('M j, H:i') }} - {{ $route->end_time->format('H:i') }}
                                            </small>
                                        @endif
                                        @if($route->estimated_distance)
                                            <small class="text-muted">
                                                <i class="fas fa-route"></i> {{ $route->estimated_distance }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($route->user)
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                                 style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                                {{ substr($route->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $route->user->name }}</div>
                                                <small class="text-muted">{{ $route->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="fas fa-user-slash"></i> No user</span>
                                    @endif
                                </td>
                                <td>
                                    @if($route->day)
                                        <span class="badge badge-info">
                                            <i class="far fa-calendar"></i> {{ $route->day->name ?? 'Day ' . $route->day->id }}
                                        </span>
                                    @else
                                        <span class="text-muted"><i class="far fa-calendar-times"></i> No day</span>
                                    @endif
                                </td>
                                <td>
                                    @if($route->area)
                                        <span class="badge badge-light border">
                                            <i class="fas fa-map-marker-alt"></i> {{ $route->area }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-{{ $route->priority <= 3 ? 'danger' : ($route->priority <= 6 ? 'warning' : 'success') }}"
                                          style="font-size: 12px; min-width: 30px;">
                                        {{ $route->priority ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($route->estimated_duration)
                                        <span class="text-success font-weight-bold">
                                            <i class="fas fa-stopwatch"></i> {{ $route->estimated_duration }}min
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $route->is_active ? 'success' : 'secondary' }}">
                                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                        {{ $route->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.routes.show', $route) }}" 
                                           class="btn btn-info" 
                                           title="View Details"
                                           data-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.routes.edit', $route) }}" 
                                           class="btn btn-primary" 
                                           title="Edit Route"
                                           data-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                title="Delete Route"
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{ $route->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $route->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete route <strong>"{{ $route->name }}"</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.routes.destroy', $route) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete Route</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-route fa-3x mb-3"></i>
                                        <h4>No Routes Found</h4>
                                        <p>Get started by creating your first route.</p>
                                        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create Route
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="d-block d-md-none">
                @forelse($routes as $route)
                    <div class="card m-3 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-primary">#{{ $route->id }} {{ $route->name }}</strong>
                                <span class="badge badge-{{ $route->is_active ? 'success' : 'secondary' }} ml-2">
                                    {{ $route->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('admin.routes.show', $route) }}">
                                        <i class="fas fa-eye text-info mr-2"></i>View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.routes.edit', $route) }}">
                                        <i class="fas fa-edit text-primary mr-2"></i>Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal{{ $route->id }}">
                                        <i class="fas fa-trash mr-2"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">User</small>
                                    <div class="font-weight-bold">
                                        {{ $route->user->name ?? 'No user' }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Day</small>
                                    <div>
                                        {{ $route->day->name ?? 'No day' }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Area</small>
                                    <div>{{ $route->area ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Priority</small>
                                    <div>
                                        <span class="badge badge-{{ $route->priority <= 3 ? 'danger' : ($route->priority <= 6 ? 'warning' : 'success') }}">
                                            {{ $route->priority ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Duration</small>
                                    <div>{{ $route->estimated_duration ? $route->estimated_duration . ' min' : 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Distance</small>
                                    <div>{{ $route->estimated_distance ?? 'N/A' }}</div>
                                </div>
                            </div>
                            @if($route->start_time && $route->end_time)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="text-muted">Time</small>
                                        <div>
                                            <i class="far fa-clock text-muted mr-1"></i>
                                            {{ $route->start_time->format('M j, H:i') }} - {{ $route->end_time->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-route fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Routes Found</h5>
                        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Create First Route
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if($routes->hasPages())
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $routes->links() }}
                </div>
                <div class="float-left">
                    <small class="text-muted">
                        Showing {{ $routes->firstItem() ?? 0 }} to {{ $routes->lastItem() ?? 0 }} of {{ $routes->total() }} routes
                    </small>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .user-avatar {
            font-weight: bold;
            text-transform: uppercase;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
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
        });
    </script>
@stop