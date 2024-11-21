<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
class AuthController extends Controller
{
    public function register(Request $request){
        // $data=$request->validate([
        //     'email'=>['required','email','unique:users'],
        //     'password'=>['required','min:6','confirmed'],
        //     'name'=>['required','string'],

        // ]);
        $validator=Validator::make($request->all(),[
            'email'=>['required','email','unique:users'],
                'password'=>['required','min:6','confirmed'],
                'name'=>['required','string'],
  ]);
  if($validator->fails()){
      return response()->json([
          'errors'=>$validator->errors()
      ],422);
  }
  $data=$request->only('email','password','name');
        $user=User::create($data);
        $token=$user->createToken('auth_token')->plainTextToken;
        return [
            'status'=>'success',
        'user'=>$user,
        'token'=>$token
        ];
            }
        
            public function login(Request $request){
                // $data=$request->validate([
                //     'email'=>['required','email','exists:users'],
                //     'password'=>['required','min:6'],
                 
                // ]);
                $validator=Validator::make($request->all(),[
                          'email'=>['required','email','exists:users'],
                  'password'=>['required','min:6'], 
                ]);
                if($validator->fails()){
                    return response()->json([
                        'errors'=>$validator->errors()
                    ],422);
                }
                $data=$request->only('email','password');
                $user=User::where('email',$data['email'])->first();
                if(!$user || !Hash::check($data['password'],$user->password)){
                    return response([
                        'status'=>'fail',
                        'message'=>'Email or Password is incorrect'
                    ],401);
                }
                $token=$user->createToken('auth_token')->plainTextToken;
                return [
                    'status'=>'success',
                'user'=>$user,
                'token'=>$token
                ];
                    }

                    public function logout(Request $request){
$request->user()->tokens()->delete();
return response()->json([
'status'=>'true',
'message'=>'Logged out successfully'
]);
                    }
}
