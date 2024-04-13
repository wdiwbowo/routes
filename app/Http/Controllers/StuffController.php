<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Stuff;
use Illuminate\Http\Request;

class StuffController extends Controller
{
    public function index()
    {
        try {
            // ambil data yang mau ditampilkan
            $data = Stuff::all()->toArray();

            return ApiFormatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request) 
    {
        try{
            // validasi
            // 'nama_colum' => 'validasi'
            $this->validate($request, [
                'name' => 'required|min:3',
                'category' => 'required',
            ]);

            $prosesData = Stuff::create([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'succes', $prosesData);
            } else {
                return ApiFormatter::sendResponse(400, 'bed request', 'Gagal memproses tambah data stuff! silahkan coba lagi.');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bed request', $err->getMessage());
        }
    }

    // $id : dari route yang ada {}
    public function show($id)
    {
        try {
            $data = Stuff::where('id', $id)->first();
            // first() : kalau gaada, tetap succes  data kosong
            //firstofali() : kalau gaada, munculnya error
            //find() : mencari berdasarkan primary key
            //where() : mencari colum spesific tertentu

            return ApiFormatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    // request : data yang dikirim 
    // $id : data yang akan diupdate, dr route {}
    public function update(Request $request, $id)
    {
        try {
            $this-> validate($request, [
                'name' => 'required',
                'category' => 'required',
            ]);

            $checkProsess = Stuff::where('id', $id)->update([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            if ($checkProsess) {
                // ::create([]) : menghasilkan data yang di tambah
                // ::update([]) : menghasilkan bolean, jadi buat ambil data terbaru dicari id

                $data = Stuff::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'succes', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $checkProsess = Stuff::where('id', $id)->delete();

            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'succes', 'Berhasil hapus data stuff');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash()
    {
        try {
            // restore : mengembalikan data spesifik yang dihapus/menghapus deleted_at nya

            $data = Stuff::onlyTrashed()->get();
            return ApiFormatter::sendResponse(200, 'succes', $data);
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $checkProsess = Stuff::onlyTrashed()->where('id', $id)->restore();

            if ($checkProsess) {
                $data = Stuff::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'succes', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMassage());
        }
    }

    public function permanentDelete($id)
    {
        try{
            $checkPermanentDelete = Stuff::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkPermanentDelete) {
                return ApiFormatter::sendResponse(200, 'succes', 'Berhasil menghapus permanent data stuff');
            } 
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMassage());
       }
    }
}