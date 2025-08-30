@extends('adminlte::page')


@section('content')
<div class="container-fluid">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="small-box">
                <div class="inner">
                    <h3>{{ $shopsCount }}</h3>
                    <p>Shops</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="inner">
                    <h3>{{ $routesCount }}</h3>
                    <p>Routes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="inner">
                    <h3>{{ $usersCount }}</h3>
                    <p>Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box">
                <div class="inner">
                    <h3>{{ $todayCheckins }}</h3>
                    <p>Today's Checkins</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
