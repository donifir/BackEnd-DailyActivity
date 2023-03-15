<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\FrirndstResource;
use App\Models\Friend;
use App\Models\FriendUser;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $auth_id)
    {
        //
        $dataFriendId = Friend::where('auth_id', $auth_id)->get();
        $response = ([
            'success' => true,
            'message' => 'list friend',
            'data' => FriendResource::collection($dataFriendId)
        ]);
        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addFreiend(Request $request, string $authId)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $friend = Friend::where('auth_id', $authId)->where('user_id', $user->id)->first();
        }
        if (!$user) {
            $response = ([
                'success' => false,
                'message' => 'email tidak tedaftar',
                'data' => $user
            ]);
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        elseif ($friend) {
            $response = ([
                'success' => false,
                'message' => 'sudah ditambahkan menjadi teman',
                'data' =>''
            ]);
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }else{
            $data=Friend::create([
                'auth_id'=>$authId,
                'user_id'=> $user->id
            ]);
            $response = ([
                'success' => true,
                'message' => 'sudah menjadi teman',
                'data' => new FriendResource($data)
            ]);
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
        //
        Friend::find($id)->delete();
        $response = ([
            'success' => true,
            'message' => 'sudah menjadi teman',
            'data' => null
        ]);
        return response()->json($response, Response::HTTP_OK);
    }
}
