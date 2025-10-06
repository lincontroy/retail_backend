@extends('adminlte::page')

@section('title', 'Create Shop')

@section('content_header')
    <h1>Create Shop</h1>
@stop

@section('content')
<form action="{{ route('admin.shops.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Basic Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Shop Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                        @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude') }}" class="form-control">
                        @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude') }}" class="form-control">
                        @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Route Assignments</h3>
        </div>
        <div class="card-body">
            <div id="routes-container">
                <div class="route-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Route</label>
                                <select name="route_ids[]" class="form-control route-select">
                                    <option value="">Select Route</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Order</label>
                                <input type="number" name="orders[]" class="form-control" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Arrival Time</label>
                                <input type="time" name="estimated_arrivals[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Departure Time</label>
                                <input type="time" name="estimated_departures[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Duration (min)</label>
                                <input type="number" name="duration_minutes[]" class="form-control" min="1" placeholder="30">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block remove-route" style="display: none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="route_notes[]" class="form-control" rows="2" placeholder="Route-specific notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" id="add-route" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Add Another Route
            </button>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Create Shop</button>
        <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const routesContainer = document.getElementById('routes-container');
        const addRouteBtn = document.getElementById('add-route');
        
        addRouteBtn.addEventListener('click', function() {
            const newRoute = routesContainer.firstElementChild.cloneNode(true);
            const select = newRoute.querySelector('.route-select');
            const removeBtn = newRoute.querySelector('.remove-route');
            
            // Clear values
            select.value = '';
            newRoute.querySelector('input[name="orders[]"]').value = '';
            newRoute.querySelector('input[name="estimated_arrivals[]"]').value = '';
            newRoute.querySelector('input[name="estimated_departures[]"]').value = '';
            newRoute.querySelector('input[name="duration_minutes[]"]').value = '';
            newRoute.querySelector('textarea[name="route_notes[]"]').value = '';
            
            // Show remove button
            removeBtn.style.display = 'block';
            
            routesContainer.appendChild(newRoute);
        });
        
        // Remove route
        routesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-route')) {
                if (routesContainer.children.length > 1) {
                    e.target.closest('.route-item').remove();
                }
            }
        });
    });
</script>
@stop