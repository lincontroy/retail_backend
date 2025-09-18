@extends('adminlte::page')

@section('title', 'Add Product')

@section('content_header')
    <h1 class="mb-4">Add New Product</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="number" name="barcode" id="barcode" class="form-control" value="{{ old('barcode') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="supplier_reference">Supplier Reference</label>
                    <input type="number" name="supplier_reference" id="supplier_reference" class="form-control" value="{{ old('supplier_reference') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="english_description">English Description</label>
                    <input type="text" name="english_description" id="english_description" class="form-control" value="{{ old('english_description') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" name="image" id="image" class="form-control-file">
                    <small class="form-text text-muted">Upload a product image (optional)</small>
                </div>
                
                <div class="form-group">
                    <label for="increment">Increment</label>
                    <input type="number" name="increment" id="increment" class="form-control" value="{{ old('increment') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="pcb">PCB (Number of pieces in 1 carton)</label>
                    <input type="number" name="pcb" id="pcb" class="form-control" value="{{ old('pcb') }}" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
@stop