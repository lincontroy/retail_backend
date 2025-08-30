<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\RouteModel;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $routes = RouteModel::all(); // fetch all routes
        $shops = Shop::with('route','users')->paginate(20);
        return view('admin.shops.index', compact('shops','routes'));
    }

    public function create()
    {
        $routes = RouteModel::all();
        return view('admin.shops.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'code'=>'nullable|string|unique:shops,code',
            'address'=>'nullable|string',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'route_id'=>'nullable|exists:routes,id'
        ]);

        Shop::create($data);
        return redirect()->route('admin.shops.index')->with('success','Shop created');
    }
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        $routes = RouteModel::all();
        return view('admin.shops.edit', compact('shop','routes'));
    }

    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $data = $request->validate([
            'name'=>'required|string',
            'code'=>'nullable|string|unique:shops,code,' . $shop->id,
            'address'=>'nullable|string',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'route_id'=>'nullable|exists:routes,id'
        ]);

        $shop->update($data);
        return redirect()->route('admin.shops.index')->with('success','Shop updated successfully');
    }
}
