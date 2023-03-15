<?php

namespace App\Http\Controllers;

use App\Models\personal_akses_token;
use App\Models\ResetPasswod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;


class AuthController extends Controller
{

    /**resetPassword
     * Show the form for creating a new resource.
     */

     public function resetPassword( string $email)
    {
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            $response = [
                'suceess' => false,
                'messages' => 'User does not exist',
                'data' => ''
            ];
            return response()->json($response, Response::HTTP_OK);
        } else {
            $dataCreateToken=ResetPasswod::create([
                'email' => $email,
                'token' => Str::random(32),
                'created_at' => Carbon::now()
            ]);
            $encrypToken=encrypt($dataCreateToken->token);
            $details = [
                'title' => 'Mail from KucingCoding',
                'body' => 'untuk reset password klik link : ',
                'mail'=>$email,
                'token'=>$encrypToken
            ];
            Mail::to($email)->send(new \App\Mail\PasswordResetMail($details));
            // dd("Email sudah terkirim.")
            $response = [
                'suceess' => false,
                'message' => 'email send',
                'data' => ''
            ];
            return response()->json($response, Response::HTTP_OK);
        }
       
    }
    public function cekResetPassword(string $email,string $token)
    {
        $valueEmail=$email;
        $decryptToken = decrypt($token);
        $validasiToken=ResetPasswod::where('email',$email)->where('token',$decryptToken)->first();
        if ($validasiToken) {
            return view('user.resetPassword', compact('validasiToken'));
        }else{
            return view('user.gagal');
        }
       
    }
    public function storeResetPassword(Request $request,  string $email)
    {
        $request->validate([
            'password' => 'required|max:60|min:8',
            'confirm_password' => 'required|max:60|min:8|same:password',
        ]);
        $user=User::where('email',$email)->first();
        ResetPasswod::where('email',$email)->delete();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        // return redirect('/selesai');
        return view('user.selesai');
    }

    public function verifikasiview($link)
    {
        $email = decrypt($link);
        $user=User::where('email',$email)->first();
        if ( $user) {
   
             $user->update([
                'email_verified_at'=>Carbon::now()
            ]);
            return view('user.verikasi');
        }
       
    }
    public function verifikasiakun(Request $request,  string $email)
    {
        // $hashmail=Hash::make($email);
        $hashmail=encrypt($email);
        $details = [
            'title' => 'Mail from KucingCoding',
            'body' => 'untuk verikasi akun klik link : ',
            'mail'=>$hashmail
        ];
        //Mail :: send (new \App\Mail\OrderPlaced);
        Mail::to($email)->send(new \App\Mail\MyTestMail($details));
        // dd("Email sudah terkirim.");
        $hashmail=Hash::make($email);
        $response = [
            'suceess' => false,
            'message' => 'email send',
            'data' => ''
        ];
        return response()->json($response, Response::HTTP_OK);

    }
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
            // $token = $user->createToken($user->email . '_Token')->plainTextToken;
            $token = $user->createToken($user->email)->plainTextToken;
            $response = [
                'suceess' => true,
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'token' => $token,
                'email_verified_at' => $user->email_verified_at,
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
            // $token = $user->createToken($user->email . '_Token')->plainTextToken;
            $token = $user->createToken($user->email)->plainTextToken;
            $response = [
                'suceess' => true,
                'messages' => 'Login Succesfully',
                'name' => $user->name,
                'user_id' => $user->id,
                'token' => $token,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        //
        $user->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();

        // $user->tokens()->where('token',$request->token)->delete();
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

    public function passwordUpdate(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            'password' => 'required|max:60|min:8',
            'new_password' => 'required|max:60|min:8',
            'confirm_new_password' => 'required|max:60|min:8|same:new_password',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($validation->fails()) {
            $response = [
                'suceess' => false,
                'messages' => $validation->errors(),
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
                'suceess' => false,
                'messages' => 'invalid credensial',
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            $response = [
                'suceess' => true,
                'messages' => 'password updated',
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }
    public function cekLogin(Request $request, string $email)
    {
        
        $data = User::where('email', $email)->firstOr(function () {});
        if ($request->user('sanctum')) {
            $response = [
                'suceess' => true,
                'messages' => 'auth',
                'data'=>$data,
            ];
        } else {
            $response = [
                'suceess' => true,
                'messages' => 'guest',
            ];
        }



        return response()->json($response, Response::HTTP_OK);
    }
}
