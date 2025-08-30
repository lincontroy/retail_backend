@extends('adminlte::page')

@section('title', 'Add Shop')

@section('content_header')
    <h1>Add Shop</h1>
@stop

@section('content')
    <form action="{{ route('admin.shops.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Shop Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Shop Code</label>
            <input type="text" name="code" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" class="form-control">
        </div>

        <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" class="form-control">
        </div>

        <div class="mb-3">
            <label>Route</label>
            <select name="route_id" class="form-control">
                <option value="">-- Select Route --</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}">{{ $route->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
@stop
