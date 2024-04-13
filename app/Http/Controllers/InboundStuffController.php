<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\InboundStuff;
use App\Helpers\ApiFormatter;
use App\Models\StuffStock;
use App\Models\Stuff;

class InboundStuffController extends Controller
{
    public function store(Request $request)
    {
       
        try {
           
            $this->validate($request,[
                'stuff_id' => 'required',
                'total' => 'required',
                'date' => 'required',
                //proff_file : type file image 
                'proff_file' => 'required|image',
            ]);
             

               

                // $request->file():ambil data yg tipe nya file
                //getClientOriginalName: ambil nama asli dari filr yg di upload 
                // Str::random(jummlah karakter) : genrate random karakter sebanyak jumlah
            $nameImage = Str::random(5) .  "_". $request->file('proff_file')->getClientOriginalName();
            //move(): memnidahkan file yg di upload ke folder public, dan nama filenya mau apa
            $request->file('proff_file')->move('upload-images', $nameImage);
            // ambil url untuk menampilkan gambarnya
            $pathImage = url('upload-images/' . $nameImage);
        
          $inboundData = InboundStuff::create([
            'stuff_id' => $request->stuff_id,
                'total' => $request->total,
                'date' => $request->date,
                'proof_file' => $pathImage,
          ]);

          if ($inboundData) {
            $stockData = StuffStock::where('stuff_id', $request->stuff_id)->first();
            if ($stockData) { //kalau data stuffstock yg stuff_id nya kaya yg di buat ada
                $total_avaible = (int)$stockData['total_avaible'] + (int)$request->total; //(int): memastikan kalau dia integer, klo engga integer diuabah jadi integer
                $stockData->update(['total_avaible' => $total_avaible]);
            }else { // kalau stocknya belukm dibuat 
                StuffStock::create([
                    'stuff_id' => $request->stuff_id,
                    'total_avaible' => $request->total,
                    'total_defect' => 0,

                ]);
            }
            $stuffWithInboundAndStock = Stuff::where('id', $request->stuff_id)->with('inboundStuff','stuffStock')->first();
            return ApiFormatter::sendResponse(200, 'success', $stuffWithInboundAndStock);

          }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse( 400,'bad request', $err->getMessage());
            
        }
    }

    public function destroy($id)
    {
        try {
            $inboundData = InboundStuff::where('id', $id)->first();

            $total_avaible = (int)$inboundData['total_avaible'] - (int)$inboundData['total'];
            $minusTotalStock = StuffStock::where('stuff_id', $inboundData['stuff_id'])->update(['total_avaible' => $total_avaible]);

            if ($minusTotalStock) {
                $updatedStuffwhithInboundAndStock = Stuff::where('id', $inboundData['stuff_id'])->with('inboundStuff', 'stuffStock')->first();

                $inboundData->delete();
                return ApiFormatter::sendResponse(200, 'succes', $updatedStuffwhithInboundAndStock);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    public function trash()
    {
        try{
            $data= InboundStuff::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }catch(\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    
    public function restore(InboundStuff $inboundStuff, $id)
    {
        try {
            // Memulihkan data dari tabel 'inbound_stuffs'
            $checkProses = InboundStuff::onlyTrashed()->where('id', $id)->restore();
    
            if ($checkProses) {
                // Mendapatkan data yang dipulihkan
                $restoredData = InboundStuff::find($id);
    
                // Mengambil total dari data yang dipulihkan
                $totalRestored = $restoredData->total;
    
                // Mendapatkan stuff_id dari data yang dipulihkan
                $stuffId = $restoredData->stuff_id;
    
                // Memperbarui total_available di tabel 'stuff_stocks'
                $stuffStock = StuffStock::where('stuff_id', $stuffId)->first();
                
                if ($stuffStock) {
                    // Menambahkan total yang dipulihkan ke total_available
                    $stuffStock->total_available += $totalRestored;
    
                    // Menyimpan perubahan pada stuff_stocks
                    $stuffStock->save();
                }
    
                return ApiFormatter::sendResponse(200, 'success', $restoredData);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengembalikan data!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function deletePermanent(InboundStuff $inboundStuff, Request $request, $id)
    {
        try {
            $getInbound = InboundStuff::onlyTrashed()->where('id',$id)->first();

            unlink(base_path('public/proof/'.$getInbound->proof_file));
            // Menghapus data dari database
            $checkProses = InboundStuff::where('id', $id)->forceDelete();
    
            // Memberikan respons sukses
            return ApiFormatter::sendResponse(200, 'success', 'Data inbound-stuff berhasil dihapus permanen');
        } catch(\Exception $err) {
            // Memberikan respons error jika terjadi kesalahan
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }   
    
    private function deleteAssociatedFile(InboundStuff $inboundStuff)
    {
        // Mendapatkan jalur lengkap ke direktori public
        $publicPath = $_SERVER['DOCUMENT_ROOT'] . '/public/proof';

    
        // Menggabungkan jalur file dengan jalur direktori public
         $filePath = public_path('proof/'.$inboundStuff->proof_file);
    
        // Periksa apakah file ada
        if (file_exists($filePath)) {
            // Hapus file jika ada
            unlink(base_path($filePath));
        }
    }
    
}