<?php

namespace App\Http\Controllers;

use App\Http\Resources\PengingatResource;
use App\Models\Pengingat;
use App\Models\PengingatUserModel;
use App\Models\User;
use App\Models\UserPostModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PengingatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        //
        $pengingat = Pengingat::where('user_id',$id)->get();
        $response = [
            'success' => 'true',
            'message' => 'list semua pengingat',
            'data' => $pengingat,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function pengingatList(string $id)
    {
        //
        $user=User::find($id);
        $pengingat = $user->pengingats()->get();
        $response = [
            'success' => 'true',
            'message' => 'list semua pengingat',
            'data' => $pengingat
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
            'nama_pengingat' => 'required',
            'keterangan_pengingat' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 'false',
                'message' => $validator->errors(),
                'data' => '',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $pengingat = Pengingat::create([
                'nama_pengingat' => $request->nama_pengingat,
                'keterangan_pengingat' => $request->keterangan_pengingat
            ]);

            $pengingatId = Pengingat::orderBy('id','desc')->first();
            PengingatUserModel::create([
                'user_id'=>$request->user_id,
                'pengingat_id'=>$pengingatId->id
            ]);

            $response = [
                'success' => 'false',
                'message' => 'data created',
                'data' => $pengingat,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $edit_pengingat)
    {
        //
        $validator = Validator::make($request->all(), [
            'nama_pengingat' => 'required',
            'keterangan_pengingat' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 'false',
                'message' => $validator->errors(),
                'data' => '',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $pengingat=Pengingat::find($edit_pengingat);
            $pengingat->update([
                'nama_pengingat' => $request->nama_pengingat,
                'keterangan_pengingat' => $request->keterangan_pengingat
            ]);
            
            $response = [
                'success' => 'false',
                'message' => 'data updated',
                'data' => $pengingat,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Pengingat::find($id)->delete();
        $response = [
            'success' => 'false',
            'message' => 'data deleted',
            'data' => '',
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
