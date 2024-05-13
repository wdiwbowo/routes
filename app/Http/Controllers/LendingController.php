<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\StuffStock;
use Illuminate\Http\Request;
use App\Models\Lending;

class LendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'date_time' => 'required',
                'name' => 'required',
                'total_stuff' => 'required',
            ]);

            $totalAvailable = StuffStock::where('stuff_id', $request->stuff_id)->value('total_avaible');

            if (is_null($totalAvailable)) {
                return ApiFormatter::sendResponse(400, 'bad request', 'Stock tidak tersedia!');
            } else {
                $lending = Lending::create([
                    'stuff_id' => $request->stuff_id,
                    'date_time' => $request->date_time,
                    'name' => $request->name,
                    'notes' => $request->notes ? $request->notes : '-',
                    'total_stuff' => $request->total_stuff,
                    'user_id' => auth()->user()->id,
                ]);
                $totalAvailableNow = (int) $totalAvailable - (int) $request->total_stuff;
                $stuffStock = StuffStock::where('stuff_id', $request->stuff_id)->update([ 'total_avaible' => $totalAvailableNow ]);

                $dataLending = Lending::where('id', $lending['id'])->with('user', 'stuff', 'stuff.stuffStock')->first();
                return ApiFormatter::sendResponse(200, 'success', $dataLending); 
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    public function index()
    {
        try{
            $data = Lending::with('stuff', 'user', 'restoration')->get();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    public function destroy($id){
        try{
            $lending = Lending::where('id', $id)->first();
    
            if($lending) {
                if($lending->restoration()->exists()){
                    return ApiFormatter::sendResponse(400, 'bad request', 'Data peminjaman sudah memiliki pengembalian barang');
                } else {
                    $stuffStock = StuffStock::where('stuff_id', $lending->stuff_id)->first();
                    $stuffStock->total_avaible += $lending->total_stuff;
                    $stuffStock->save();
    
                    $lending->delete();
    
                    $data = Lending::with('stuff', 'user', 'restoration')->get();
                    
                    return ApiFormatter::sendResponse(200, 'success', $data);
                }
            } else {
                return ApiFormatter::sendResponse(404, 'bad request', 'Data peminjaman tidak ditemukan');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}
