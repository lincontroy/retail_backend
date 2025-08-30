<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RouteModel;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = RouteModel::withCount('shops')->paginate(20);
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'notes'=>'nullable|string'
        ]);
        RouteModel::create($data);
        return redirect()->route('admin.routes.index')->with('success','Route created');
    }
}
