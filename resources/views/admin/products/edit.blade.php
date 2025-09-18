@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content_header')
    <h1 class="mb-4">Edit Product</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="number" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="supplier_reference">Supplier Reference</label>
                    <input type="number" name="supplier_reference" id="supplier_reference" class="form-control" value="{{ old('supplier_reference', $product->supplier_reference) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="english_description">English Description</label>
                    <input type="text" name="english_description" id="english_description" class="form-control" value="{{ old('english_description', $product->english_description) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', $product->brand) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->english_description }}" style="max-width: 100px; max-height: 100px;">
                            <br>
                            <small>Current image</small>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="form-control-file">
                    <small class="form-text text-muted">Upload a new image to replace the current one</small>
                </div>
                
                <div class="form-group">
                    <label for="increment">Increment</label>
                    <input type="number" name="increment" id="increment" class="form-control" value="{{ old('increment', $product->increment) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="pcb">PCB (Number of pieces in 1 carton)</label>
                    <input type="number" name="pcb" id="pcb" class="form-control" value="{{ old('pcb', $product->pcb) }}" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
@stop