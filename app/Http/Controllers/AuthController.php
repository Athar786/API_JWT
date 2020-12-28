<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'surname'=>['required', 'string', 'max:255'],
            'username'=>['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number'=>['required'],
            'company'=>['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'name'=>$request->name,
            'surname'=>$request->surname,
            'username'=>$request->username,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'company'=>$request->company,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json([
            'message'=>'User succesfully registered',
            'user'=>$user
        ],201);
        // return redirect('home');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['message'=>'Successfully Updated']);
    }

    public function update(Request $request,$id)
    {
        // dd($request->all());
        // return response()->json($request->all());
        $user = User::find($id);
        if(!$user)
        {
            return response()->json([
                'success'=>false,
                'message'=>'Sorry,id ' . $id . ' cannot be found'
            ],400);
        }

        $update  = $user->fill($request->all())->save();
        if($update)
        {
            return response()->json([
                'success'=>true
            ]);
        } else {
            return response()->json([
                'success'=>false,
                'message' => 'Sorry, User could not be updated'
            ],500);
        }
    }

    public function login(Request $request)
    {
       $validator = Validator::make($request->all(),[
            'email'=>'required|email|string',
            'password'=>'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }

        if(!$token = auth()->attempt($validator->validated()))
        {
            return response()->json(['error'=>'Unauthorized'],401);
        }

        return $this->createNewToken($token);
        //return redirect('login')->with('error', 'You have entered invalid credentials');
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function me() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        if(auth()->user()->email_verified_at == null){
            return response()->json([
                'success'=>false,
                'message'=>'Plz verify your email'
            ]);
        } else{
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user()
            ]);
        }

    }

}
