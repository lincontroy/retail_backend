@extends('adminlte::page')

@section('title', 'Create Route')

@section('content_header')
    <h1>Create Route</h1>
@stop

@section('content')
<form action="{{ route('admin.routes.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Area</label>
                <input type="text" name="area" value="{{ old('area') }}" class="form-control">
                @error('area') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>User</label>
                <select name="user_id" class="form-control">
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                        <option value="{{ $day->id }}" {{ old('day_id') == $day->id ? 'selected' : '' }}>
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
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="form-control">
                @error('start_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>End Time</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="form-control">
                @error('end_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Priority (1-10)</label>
                <input type="number" name="priority" value="{{ old('priority', 5) }}" min="1" max="10" class="form-control">
                @error('priority') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Duration (minutes)</label>
                <input type="number" name="estimated_duration" value="{{ old('estimated_duration') }}" class="form-control">
                @error('estimated_duration') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Distance</label>
                <input type="text" name="estimated_distance" value="{{ old('estimated_distance') }}" class="form-control" placeholder="e.g., 5 km">
                @error('estimated_distance') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ old('is_active', 1) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', 1) ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
        @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Create Route</button>
        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@stop