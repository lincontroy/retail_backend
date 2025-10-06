@extends('adminlte::page')

@section('title', 'Manage Shop Users')

@section('content_header')
    <h1>Manage Users for: {{ $shop->name }}</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Multiple Users to Shop</h3>
                    <small class="text-muted">Select one or more users to assign to this shop</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shops.update-users', $shop) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label><strong>Select Users</strong></label>
                            <select name="user_ids[]" class="form-control select2" multiple="multiple" 
                                    data-placeholder="Choose users..." style="width: 100%;" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ $shopUsers->contains('id', $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Hold Ctrl/Cmd to select multiple users. Selected users will be assigned to this shop.
                            </small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update User Assignments
                            </button>
                            <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Shop
                            </a>
                            <a href="{{ route('admin.shops.index') }}" class="btn btn-light">
                                <i class="fas fa-store"></i> All Shops
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Users</h3>
                    <span class="badge badge-primary badge-pill float-right">{{ $shopUsers->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($shopUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($shopUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <div class="btn-group">
                                            <form action="{{ route('admin.shops.remove-user', [$shop, $user]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Remove {{ $user->name }} from this shop?')"
                                                        title="Remove User">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-4">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No users assigned</p>
                            <small class="text-muted">Select users from the left to assign them</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Shop Summary</h3>
                </div>
                <div class="card-body">
                    <p><strong>Shop Name:</strong> {{ $shop->name }}</p>
                    <p><strong>Address:</strong> {{ $shop->address ?? 'N/A' }}</p>
                    <p><strong>Total Routes:</strong> 
                        <span class="badge badge-info">{{ $shop->routes->count() }}</span>
                    </p>
                    <p><strong>Total Users:</strong> 
                        <span class="badge badge-success">{{ $shopUsers->count() }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 120px;
        padding: 5px;
    }
    .list-group-item {
        border: none;
        padding: 1rem;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Choose users...',
            allowClear: true
        });
    });
</script>
@stop