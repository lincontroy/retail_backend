@extends('adminlte::page')

@section('title', 'Manage Shop Routes')

@section('content_header')
    <h1>Manage Routes for: {{ $shop->name }}</h1>
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
            <h3 class="card-title">Current Route Assignments</h3>
            <div class="card-tools">
                <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Shop
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.shops.update-routes', $shop) }}" method="POST" id="routes-form">
                @csrf
                
                <div id="routes-container">
                    @if($shopRoutes->count() > 0)
                        @foreach($shopRoutes as $index => $route)
                            <div class="route-item card mb-3">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Route Assignment #{{ $index + 1 }}</h5>
                                        <button type="button" class="btn btn-sm btn-danger remove-route" {{ $shopRoutes->count() <= 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Route *</label>
                                                <select name="routes[{{ $index }}][route_id]" class="form-control" required>
                                                    <option value="">Select Route</option>
                                                    @foreach($routes as $routeOption)
                                                        <option value="{{ $routeOption->id }}" 
                                                            {{ $routeOption->id == $route->id ? 'selected' : '' }}>
                                                            {{ $routeOption->name }} 
                                                            @if($routeOption->area)
                                                                ({{ $routeOption->area }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input type="number" name="routes[{{ $index }}][order]" 
                                                       value="1" 
                                                       class="form-control" min="0" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Estimated Arrival</label>
                                                <input type="time" name="routes[{{ $index }}][estimated_arrival]" 
                                                       value="{{ $route->pivot->estimated_arrival }}" 
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Estimated Departure</label>
                                                <input type="time" name="routes[{{ $index }}][estimated_departure]" 
                                                       value="{{ $route->pivot->estimated_departure }}" 
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Duration (minutes)</label>
                                                <input type="number" name="routes[{{ $index }}][duration_minutes]" 
                                                    
                                                       class="form-control" min="1" 
                                                       placeholder="30" value="30">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label>Notes</label>
                                                <textarea name="routes[{{ $index }}][notes]" 
                                                          class="form-control" 
                                                          rows="2" 
                                                          placeholder="Route-specific notes...">{{ $route->pivot->notes }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="route-item card mb-3">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Route Assignment #1</h5>
                                    <button type="button" class="btn btn-sm btn-danger remove-route" disabled>
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Route *</label>
                                            <select name="routes[0][route_id]" class="form-control" required>
                                                <option value="">Select Route</option>
                                                @foreach($routes as $route)
                                                    <option value="{{ $route->id }}">
                                                        {{ $route->name }} 
                                                        @if($route->area)
                                                            ({{ $route->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Order</label>
                                            <input type="number" name="routes[0][order]" 
                                                   class="form-control" min="0" 
                                                   placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Estimated Arrival</label>
                                            <input type="time" name="routes[0][estimated_arrival]" 
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Estimated Departure</label>
                                            <input type="time" name="routes[0][estimated_departure]" 
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Duration (minutes)</label>
                                            <input type="number" name="routes[0][duration_minutes]" 
                                                   class="form-control" min="1" 
                                                   placeholder="30">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="routes[0][notes]" 
                                                      class="form-control" 
                                                      rows="2" 
                                                      placeholder="Route-specific notes..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" id="add-route" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Another Route
                        </button>
                        
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Route Assignments
                            </button>
                            <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($shopRoutes->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Current Route Summary</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Order</th>
                            <th>Time Slot</th>
                            <th>Duration</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shopRoutes->sortBy('pivot.order') as $route)
                            <tr>
                                <td>
                                    <strong>{{ $route->name }}</strong>
                                    @if($route->area)
                                        <br><small class="text-muted">{{ $route->area }}</small>
                                    @endif
                                </td>
                                <td>{{ $route->pivot->order ?? 'N/A' }}</td>
                                <td>
                                    @if($route->pivot->estimated_arrival && $route->pivot->estimated_departure)
                                        {{ $route->pivot->estimated_arrival }} - {{ $route->pivot->estimated_departure }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($route->pivot->duration_minutes)
                                        <span class="badge badge-info">{{ $route->pivot->duration_minutes }} min</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $route->pivot->notes ? Str::limit($route->pivot->notes, 50) : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
<style>
    .route-item {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .remove-route {
        transition: all 0.3s ease;
    }
    .remove-route:hover:not(:disabled) {
        transform: scale(1.05);
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const routesContainer = document.getElementById('routes-container');
        const addRouteBtn = document.getElementById('add-route');
        let routeCount = {{ $shopRoutes->count() > 0 ? $shopRoutes->count() : 1 }};

        // Add new route
        addRouteBtn.addEventListener('click', function() {
            const newIndex = routeCount++;
            const newRoute = routesContainer.firstElementChild.cloneNode(true);
            
            // Update all names and IDs
            newRoute.querySelectorAll('[name]').forEach(element => {
                const name = element.getAttribute('name');
                const newName = name.replace(/routes\[\d+\]/, `routes[${newIndex}]`);
                element.setAttribute('name', newName);
            });
            
            // Clear values
            newRoute.querySelector('select[name^="routes"]').value = '';
            newRoute.querySelector('input[name$="[order]"]').value = '';
            newRoute.querySelector('input[name$="[estimated_arrival]"]').value = '';
            newRoute.querySelector('input[name$="[estimated_departure]"]').value = '';
            newRoute.querySelector('input[name$="[duration_minutes]"]').value = '';
            newRoute.querySelector('textarea[name$="[notes]"]').value = '';
            
            // Update header
            const header = newRoute.querySelector('.card-header h5');
            header.textContent = `Route Assignment #${newIndex + 1}`;
            
            // Enable remove button
            const removeBtn = newRoute.querySelector('.remove-route');
            removeBtn.disabled = false;
            
            routesContainer.appendChild(newRoute);
            
            // Enable all remove buttons since we have multiple routes now
            document.querySelectorAll('.remove-route').forEach(btn => {
                btn.disabled = false;
            });
        });
        
        // Remove route
        routesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-route') || 
                e.target.closest('.remove-route')) {
                const removeBtn = e.target.classList.contains('remove-route') ? 
                    e.target : e.target.closest('.remove-route');
                const routeItem = removeBtn.closest('.route-item');
                
                if (routesContainer.children.length > 1) {
                    routeItem.remove();
                    
                    // Renumber remaining routes
                    const remainingRoutes = routesContainer.querySelectorAll('.route-item');
                    remainingRoutes.forEach((route, index) => {
                        // Update header
                        const header = route.querySelector('.card-header h5');
                        header.textContent = `Route Assignment #${index + 1}`;
                        
                        // Update all names
                        route.querySelectorAll('[name]').forEach(element => {
                            const name = element.getAttribute('name');
                            const newName = name.replace(/routes\[\d+\]/, `routes[${index}]`);
                            element.setAttribute('name', newName);
                        });
                    });
                    
                    // Disable remove button if only one route remains
                    if (routesContainer.children.length === 1) {
                        routesContainer.querySelector('.remove-route').disabled = true;
                    }
                }
            }
        });
        
        // Form validation
        document.getElementById('routes-form').addEventListener('submit', function(e) {
            const routeSelects = this.querySelectorAll('select[name$="[route_id]"]');
            let hasEmptyRoute = false;
            
            routeSelects.forEach(select => {
                if (!select.value) {
                    hasEmptyRoute = true;
                    select.classList.add('is-invalid');
                } else {
                    select.classList.remove('is-invalid');
                }
            });
            
            if (hasEmptyRoute) {
                e.preventDefault();
                alert('Please select a route for all assignments.');
            }
        });
    });
</script>
@stop