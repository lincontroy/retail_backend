@extends('adminlte::page')

@section('title', 'Create Route')

@section('content_header')
    <h1>Create Route</h1>
@stop
@section('content')
<form action="{{ route('admin.routes.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary">Save</button>
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@stop
