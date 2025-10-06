@extends('adminlte::page')

@section('title', 'Edit Route')

@section('content_header')
    <h1>Edit Route: {{ $route->name }}</h1>
@stop

@section('content')
<form action="{{ route('admin.routes.update', $route) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Same form fields as create.blade.php but with current values -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="{{ old('name', $route->name) }}" class="form-control" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Area</label>
                <input type="text" name="area" value="{{ old('area', $route->area) }}" class="form-control">
                @error('area') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- In the edit.blade.php, add these fields in the form -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $route->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Day</label>
            <select name="day_id" class="form-control">
                <option value="">Select Day</option>
                @foreach($days as $day)
                    <option value="{{ $day->id }}" {{ old('day_id', $route->day_id) == $day->id ? 'selected' : '' }}>
                        {{ $day->name ?? 'Day ' . $day->id }}
                    </option>
                @endforeach
            </select>
            @error('day_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Start Time</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time', $route->start_time ? $route->start_time->format('Y-m-d\TH:i') : '') }}" class="form-control">
                @error('start_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>End Time</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time', $route->end_time ? $route->end_time->format('Y-m-d\TH:i') : '') }}" class="form-control">
                @error('end_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- Include other form fields similar to create view -->

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Update Route</button>
        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@stop