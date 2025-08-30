@extends('adminlte::page')

@section('title', 'Edit Shop')

@section('content_header')
    <h1>Edit Shop</h1>
@stop

@section('content')
    <form action="{{ route('admin.shops.update', $shop->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Shop Name</label>
            <input type="text" name="name" class="form-control" value="{{ $shop->name }}" required>
        </div>

        <div class="mb-3">
            <label>Shop Code</label>
            <input type="text" name="code" class="form-control" value="{{ $shop->code }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ $shop->address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" class="form-control" value="{{ $shop->latitude }}">
        </div>

        <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" class="form-control" value="{{ $shop->longitude }}">
        </div>

        <div class="mb-3">
            <label>Route</label>
            <select name="route_id" class="form-control">
                <option value="">-- Select Route --</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ $shop->route_id == $route->id ? 'selected' : '' }}>
                        {{ $route->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
@stop
