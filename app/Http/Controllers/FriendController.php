<?php

namespace App\Http\Controllers;

use App\Http\Resources\FrirndstResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        //

      $data= Friend::where('uid1',$id)->get();
        $response = [
            'success' => 'false',
            'message' => 'data created',
            'data' => FrirndstResource::collection(($data)),
        ];
        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addFreiend(Request $request, string $id)
    {
        //
        $user = User::where('email',$request->email)->first();
        $friend=Friend::where('uid',$id)->where('user_id',$user->id)->first();
        if ($user && $friend) {
            $response = [
                'success' => 'user sudah ada',
            ];
            return response()->json($response, Response::HTTP_OK);
        }elseif ($user && !$friend) {
            Friend::create([
                'uid'=>$id,
                'user_id'=>$user->id
            ]);
            $response = [
                'success' => 'berhasil ditambahkan',
            ];
            return response()->json($response, Response::HTTP_OK);
        }else{
            $response = [
                'success' => 'email tidak terdaftar',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //
        $user = User::where('email',$request->email)->first();
        $friend=Friend::where('uid',$id)->where('user_id',$user->id)->first();

        if ( $user && $friend ) {
            Friend::find($friend->id)->delete();
            $response = [
                'success' => 'berhasil dihapus',
            ];
            return response()->json($response, Response::HTTP_OK);
        }else{
            $response = [
                'success' => 'error ',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
