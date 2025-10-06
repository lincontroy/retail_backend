@extends('adminlte::page')

@section('title', 'Shop-User Assignments')

@section('content_header')
    <h1>Shop-User Assignments</h1>
    <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary float-right">
        <i class="fas fa-store"></i> Back to Shops
    </a>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Shop-User Relationships</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Shop</th>
                            <th>User</th>
                            <th>User Email</th>
                            <th>Assigned Date</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shopUsers as $assignment)
                            <tr>
                                <td>{{ $assignment->pivot_id }}</td>
                                <td>
                                    <strong>{{ $assignment->shop_name }}</strong>
                                    <br><small class="text-muted">ID: {{ $assignment->shop_id }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                             style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                            {{ substr($assignment->user_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $assignment->user_name }}</div>
                                            <small class="text-muted">ID: {{ $assignment->user_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $assignment->user_email }}</td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($assignment->created_at)->format('M j, Y H:i') }}</small>
                                </td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($assignment->updated_at)->format('M j, Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.shops.show', $assignment->shop_id) }}" 
                                           class="btn btn-info" title="View Shop">
                                            <i class="fas fa-store"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.manage-users', $assignment->shop_id) }}" 
                                           class="btn btn-primary" title="Manage Users">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <form action="{{ route('admin.shops.remove-user', [$assignment->shop_id, $assignment->user_id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Remove User"
                                                    onclick="return confirm('Remove this user from the shop?')">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-link fa-3x mb-3"></i>
                                        <h4>No Shop-User Assignments Found</h4>
                                        <p>Start by assigning users to shops.</p>
                                        <a href="{{ route('admin.shops.index') }}" class="btn btn-primary">
                                            <i class="fas fa-store"></i> Manage Shops
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($shopUsers->hasPages())
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $shopUsers->links() }}
                </div>
                <div class="float-left">
                    <small class="text-muted">
                        Showing {{ $shopUsers->firstItem() ?? 0 }} to {{ $shopUsers->lastItem() ?? 0 }} of {{ $shopUsers->total() }} assignments
                    </small>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .user-avatar {
        font-weight: bold;
        text-transform: uppercase;
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
</style>
@stop