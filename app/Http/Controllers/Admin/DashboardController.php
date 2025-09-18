<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\RouteModel;
use App\Models\Checkin;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $shopsCount = Shop::count();
        $routesCount = RouteModel::count();
        $usersCount = User::count();
        $productCount = Product::count();
        $todayCheckins = Checkin::whereDate('checked_in_at', now()->toDateString())->count();

        return view('admin.dashboard', compact('shopsCount','routesCount','usersCount','todayCheckins','productCount'));
    }
}
