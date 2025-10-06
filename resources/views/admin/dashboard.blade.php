@extends('adminlte::page')


@section('content')
<div class="container-fluid">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('admin.shops.index') }}" class="card-link">
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ $shopsCount }}</h3>
                        <p>Shops</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.routes.index') }}" class="card-link">
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ $routesCount }}</h3>
                        <p>Routes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.users.index') }}" class="card-link">
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ $usersCount }}</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.products.index') }}" class="card-link">
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ $productCount }}</h3>
                        <p>Total products</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .small-box {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 120px;
        position: relative;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    .small-box .inner {
        padding: 20px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        color: #2c3e50;
    }
    .small-box p {
        font-size: 1.1rem;
        color: #7f8c8d;
        margin: 0;
    }
    .small-box .icon {
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 2.5rem;
        opacity: 0.2;
        color: #34495e;
        transition: all 0.3s ease;
    }
    .small-box:hover .icon {
        opacity: 0.4;
        transform: scale(1.1);
    }
    .card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .card-link:hover {
        color: inherit;
    }
</style>

 {{-- Table --}}
 <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Shop</th>
                <th>User</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Device Info</th>
                <th>Checked In At</th>
            </tr>
        </thead>
        <tbody>

            @php
            $checkins=App\Models\Checkin::with(['shop', 'user'])->orderBy('checked_in_at', 'desc')->take(10)->get();
            @endphp
            @if($checkins->count() > 0)
                @foreach($checkins as $checkin)
                <tr>
                    <td>{{ $checkin->id }}</td>
                    <td>{{ $checkin->shop->name ?? 'N/A' }}</td>
                    <td>{{ $checkin->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($checkin->latitude, 6) }}</td>
                    <td>{{ number_format($checkin->longitude, 6) }}</td>
                    <td>
                        @if($checkin->device_info)
                            @php
                                $deviceInfo = json_decode($checkin->device_info, true);
                            @endphp
                            @if($deviceInfo)
                                <small>
                                    <strong>{{ $deviceInfo['manufacturer'] ?? 'Unknown' }} {{ $deviceInfo['model'] ?? '' }}</strong><br>
                                    Android {{ $deviceInfo['android_version'] ?? '' }}
                                </small>
                            @else
                                <span class="text-muted">Invalid device info</span>
                            @endif
                        @else
                            <span class="text-muted">No device info</span>
                        @endif
                    </td>
                    <td>{{ $checkin->checked_in_at }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No checkins found for selected date range.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
{{-- Pagination --}}
<div class="mt-3">
   
</div>
@endsection
