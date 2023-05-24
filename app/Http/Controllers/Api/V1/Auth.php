<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Auth extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_card_number' => 'required',
            'password' => 'required'
        ],[
            'required' => ':attribute harus diisi.'
        ],[
            'id_card_number' => 'ID Card Number',
            'password' => 'Password'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $cred = $request->only(['id_card_number','password']);
        $society = Society::where('id_card_number',$cred['id_card_number'])->first();
        if(!$society){
            return response()->json([
                'message' => 'ID Card Number Or Password Incorect'
            ],401);
        }
        if(!(Hash::check($cred['password'],$society->password))){
            return response()->json([
                'message' => 'ID Card Number Or Password Incorect'
            ],401);
        }
        $token = md5($society->id);
        $society->login_tokens = $token;
        $society->update();
        return response()->json([
            'name' => $society->name,
            'born_date' => $society->born_date,
            'gender' => $society->gender,
            'address' => $society->address,
            'token' => $token,
            'regional' => $society->regional,
        ],200);
    }

    public function logout(Request $request)
    {
        if(empty($request->query('token') ?? '')){
            return response()->json([
                'message' => 'Invalid Token'
            ],401);
        }
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        if(!$society){
            return response()->json([
                'message' => 'Invalid User'
            ],401);
        }
        $society->login_tokens = null;
        $society->update();
        return response()->json([
            'message' => 'Logout Success'
        ],200);
    }
}
