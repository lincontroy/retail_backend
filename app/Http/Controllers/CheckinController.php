<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\Shop;

class CheckinController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_id'=>'required|exists:shops,id',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'device_info'=>'nullable|string'
        ]);

        $data['user_id'] = $request->user()->id;
        $checkin = Checkin::create(array_merge($data, ['checked_in_at'=>now()]));

        // Optionally: Log this action

        return response()->json(['success'=>true,'checkin'=>$checkin], 201);
    }

    public function index(Request $request)
    {
        $query = Checkin::with('user','shop')->orderBy('checked_in_at','desc');
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        return response()->json($query->paginate(20));
    }
}
