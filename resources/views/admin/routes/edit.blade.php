@extends('adminlte::page')

@section('title', 'Edit Route')

@section('content_header')
    <h1>Edit Route</h1>
@stop
@section('content')
<form action="{{ route('admin.routes.update', $route) }}" method="POST">
    @csrf @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $route->name) }}" class="form-control" required>
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes', $route->notes) }}</textarea>
        @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
