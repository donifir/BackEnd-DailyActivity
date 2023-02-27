<?php

namespace App\Http\Controllers;

use App\Models\ResetPasswod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            'nama' => 'required|max:60|min:4',
            'email' => 'required|max:60|min:3|unique:users|email',
            'password' => 'required|max:60|min:8',
            'confirm_password' => 'required|max:60|min:8|same:password',
        ]);
        if ($validation->fails()) {
            $response = [
                'suceess' => false,
                'message' => $validation->errors(),
                'data' => ''
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken($user->email . '_Token')->plainTextToken;
            $response = [
                'suceess' => true,
                'username' => $user->name,
                'token' => $token,
                'message' => 'register Successfully',
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:191',
            'password' => 'required|min:8',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($validator->fails()) {
            $response = [
                'suceess' => false,
                'messages' => $validator->errors(),
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
                'suceess' => false,
                'messages' => 'invalid credensial',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $token = $user->createToken($user->email . '_Token')->plainTextToken;
            $response = [
                'suceess' => true,
                'messages' => 'Login Succesfully',
                'token' => $token,
                'username' => $user->name,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout(string $id)
    {
        //
        auth()->user()->tokens()->delete();
        $response = [
            'suceess' => true,
            'messages' => 'Logot Succesfully',
            'data' => ''
        ];
        return response()->json($response, Response::HTTP_OK);
    }

     /**
     * Display the specified resource.
     */

    public function passwordReset(Request $request)
    {
        //
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $response = [
                'suceess' => false,
                'messages' => 'User does not exist',
                'data' => ''
            ];
            return response()->json($response, Response::HTTP_OK);
        } else {
            ResetPasswod::create([
                'email' => $request->email,
                'token' => Str::random(32),
                'created_at' => Carbon::now()
            ]);
            $response = [
                'suceess' => false,
                'messages' => 'A reset link has been sent to your email address.',
                'data' => ''
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }
}
