<?php

namespace App\Http\Controllers;

use App\Models\DetailPengingat as ModelsDetailPengingat;
use App\Models\Pengingat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DetailPengingat extends Controller
{

    public function index()
    {
        //
        $pengingat = ModelsDetailPengingat::all();
        $response = [
            'success' => 'true',
            'message' => 'list semua pengingat',
            'data' => $pengingat,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function list(string $id)
    {
        //
        // $user = User::where('email', $request->email)->first();
        $pengingat = ModelsDetailPengingat::where('id_pengingat', $id)->get();
        $response = [
            'success' => 'true',
            'message' => 'list semua pengingat',
            'data' => $pengingat,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'id_pengingat' => 'required',
            'nama_kegiatan' => 'required|max:30',
            // 'keterangan_kegiatan' => 'required|max:130',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 'false',
                'message' => $validator->errors(),
                'data' => '',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $kegiatan = ModelsDetailPengingat::create([
                'id_pengingat' => $request->id_pengingat,
                'nama_kegiatan' => $request->nama_kegiatan,
                // 'keterangan_kegiatan' => $request->keterangan_kegiatan,
                // 'mulai_kegiatan' => $request->mulai_kegiatan
            ]);
            $response = [
                'success' => 'false',
                'message' => 'data created',
                'data' => $kegiatan,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }


    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'id_pengingat' => 'required',
            'nama_kegiatan' => 'required|max:30',
            'id_pengingat' => 'required',
            'mulai_kegiatan' => 'required'

        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 'false',
                'message' => $validator->errors(),
                'data' => '',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $kegiatan = ModelsDetailPengingat::find($id);
            $kegiatan->update([
                'id_pengingat' => $request->id_pengingat,
                'nama_kegiatan' => $request->nama_kegiatan,
                'keterangan_kegiatan' => $request->keterangan_kegiatan,
                'mulai_kegiatan' => $request->mulai_kegiatan,
                // 'status'=>$request->status,
            ]);
            if ($request->mulai_kegiatan) {
                $pengingatListMax = ModelsDetailPengingat::orderBy('mulai_kegiatan', 'DESC')->where('id_pengingat', $request->id_pengingat)->get();
                $pengingatListMin = ModelsDetailPengingat::orderBy('mulai_kegiatan', 'ASC')->where('id_pengingat', $request->id_pengingat)->where('mulai_kegiatan', '<>', '')->value('mulai_kegiatan');
                $pengingat = Pengingat::find($request->id_pengingat);

                $pengingat->update([
                    'mulai_pengingat' => $pengingatListMin,
                    'selesai_pengingat' => $pengingatListMax->pluck('mulai_kegiatan')->last()
                ]);
            }
            $response = [
                'success' => 'true',
                'selesai_pengingat' =>  $pengingatListMax->pluck('mulai_kegiatan')->first(),
                'kegiatanMin' => $pengingatListMin,
                'data' => $pengingatListMax->pluck('mulai_kegiatan')->first()
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    public function updateCeklist(Request $request, string $id)
    {
        $kegiatan = ModelsDetailPengingat::find($id);
        $kegiatan->update([
            'status'=>$request->status,
        ]);
        $response = [
            'success' => 'true',
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        ModelsDetailPengingat::find($id)->delete();
        $response = [
            'success' => 'false',
            'message' => 'data deleted',
            'data' => '',
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
