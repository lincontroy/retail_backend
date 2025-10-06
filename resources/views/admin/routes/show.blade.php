@extends('adminlte::page')

@section('title', 'Route Details')

@section('content_header')
    <h1>Route Details: {{ $route->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Basic Information</h5>
                    <p><strong>Name:</strong> {{ $route->name }}</p>
                    <p><strong>Area:</strong> {{ $route->area ?? 'N/A' }}</p>
                    <p><strong>Priority:</strong> {{ $route->priority ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $route->is_active ? 'success' : 'secondary' }}">
                            {{ $route->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Timing & Distance</h5>
                    <p><strong>Start Time:</strong> {{ $route->start_time ? $route->start_time->format('M j, Y H:i') : 'N/A' }}</p>
                    <p><strong>End Time:</strong> {{ $route->end_time ? $route->end_time->format('M j, Y H:i') : 'N/A' }}</p>
                    <p><strong>Duration:</strong> {{ $route->estimated_duration ? $route->estimated_duration . ' minutes' : 'N/A' }}</p>
                    <p><strong>Distance:</strong> {{ $route->estimated_distance ?? 'N/A' }}</p>
                </div>
            </div>
            <!-- In the show.blade.php, add this section -->
<div class="row">
    <div class="col-md-4">
        <h5>Assignment</h5>
        <p><strong>User:</strong> 
            @if($route->user)
                {{ $route->user->name }} ({{ $route->user->email }})
            @else
                <span class="text-muted">No user assigned</span>
            @endif
        </p>
        <p><strong>Day:</strong> 
            @if($route->day)
                {{ $route->day->name ?? 'Day ' . $route->day->id }}
            @else
                <span class="text-muted">No day assigned</span>
            @endif
        </p>
    </div>
    <div class="col-md-4">
        <!-- Keep your existing basic information -->
        <h5>Basic Information</h5>
        <p><strong>Name:</strong> {{ $route->name }}</p>
        <p><strong>Area:</strong> {{ $route->area ?? 'N/A' }}</p>
        <p><strong>Priority:</strong> {{ $route->priority ?? 'N/A' }}</p>
    </div>
    <div class="col-md-4">
        <!-- Keep your existing timing information -->
        <h5>Timing & Distance</h5>
        <p><strong>Start Time:</strong> {{ $route->start_time ? $route->start_time->format('M j, Y H:i') : 'N/A' }}</p>
        <p><strong>End Time:</strong> {{ $route->end_time ? $route->end_time->format('M j, Y H:i') : 'N/A' }}</p>
    </div>
</div>
            
            @if($route->description)
                <div class="mt-3">
                    <h5>Description</h5>
                    <p>{{ $route->description }}</p>
                </div>
            @endif
            
            @if($route->notes)
                <div class="mt-3">
                    <h5>Notes</h5>
                    <p>{{ $route->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@stop