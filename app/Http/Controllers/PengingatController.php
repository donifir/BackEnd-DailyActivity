<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\PengingatResource;
use App\Models\Friend;
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
        $pengingat = Pengingat::where('user_id', $id)->get();
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
        $user = User::find($id);
        $pengingat = $user->pengingats()->get();
        // if ($id) {

        // }
        $response = [
            'success' => 'true',
            'message' => 'list semua pengingat',
            'data' => $pengingat
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    // public function listUserPengingat(string $pengingat_id)
    public function listUserPengingat(string $pengingat_id)
    {

        // $pengingat = Pengingat::find($pengingat_id)->users()->get();
        $pengingat = Pengingat::find($pengingat_id)->users()->get();

        $response = [
            'success' => 'true',
            'list_id_user' => 'list semua pengingat',
            'data' => $pengingat
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function daftarUserPengingat(string $pengingat_id, string $auth_id)
    {

        $pengingat = Pengingat::find($pengingat_id)->users()->get();

        $dataBefore = Friend::where('auth_id', $auth_id);
        foreach ($pengingat as $data) {
            $dataBefore->where('user_id', '<>',  $data->id);
        }
        $dataAfter = $dataBefore->get();

        $response = [
            'success' => 'true',
            'list_id_user' => 'list semua pengingat',
            'data' => FriendResource::collection($dataAfter)
            // 'data' =>  $jadi
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**daftarUserPengingat
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

            $pengingatId = Pengingat::orderBy('id', 'desc')->first();
            PengingatUserModel::create([
                'user_id' => $request->user_id,
                'pengingat_id' => $pengingatId->id
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

            if ($request->remove_id_item) {
                PengingatUserModel::where('pengingat_id', $edit_pengingat)->where('user_id', $request->remove_id_item)->delete();
                // foreach ($request->remove_id_item as $data) {
                // PengingatUserModel::where('pengingat_id', $edit_pengingat)->where('user_id', $data)->delete();
                // }
            }
            if ($request->datalistuser) {
                $dataUser = User::where('email', $request->datalistuser)->first();
                PengingatUserModel::create([
                    'user_id' => $dataUser->id,
                    'pengingat_id' => $edit_pengingat
                ]);
                // foreach ($request->datalistuser as $data) {

                // }
            }
            $pengingat = Pengingat::find($edit_pengingat);
            $pengingat->update([
                'nama_pengingat' => $request->nama_pengingat,
                'keterangan_pengingat' => $request->keterangan_pengingat
            ]);

            $response = [
                'success' => 'false',
                'message' => 'datassss',
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
