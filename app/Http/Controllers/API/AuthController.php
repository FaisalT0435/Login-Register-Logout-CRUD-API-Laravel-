<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nette\Utils\Validators;
use Validator;
use Auth;
use App\Models\User;
// use Illuminate\Support\Facades\Auth;

// use Illuminate\Validation\Validator as ValidationValidator;

// use Illuminate\Validation\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]
        );
        if ($validator->fails()){
            return response()->json([
                'succes'=> false,
                'Note' => 'ada kesalahan',
                'data' => $validator->errors()
            ]);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $succes['token'] = $user->createToken('auth_token')->plainTextToken;
        $succes['name'] = $user->name;

        return response()->json([
            'succes'=> true,
            'Note' => 'Register Sukses',
            'data' => $succes
        ]);
    }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $succes['token'] = $auth->createToken('auth_token')->plainTextToken;
            $succes['name'] = $auth->name;
            $succes['email'] = $auth->email;

            return response()->json([
                'succes'=> true,
                'Note' => 'logout Sukses',
                'data' => $succes
            ]);

        }else{
            return response()->json([
                'succes'=> false,
                'Note' => 'Logout Gagal',
                'data' => null
            ]);
        }
    }
    public function logout(Request $request){
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $succes['token'] = $auth->createToken('auth_token')->plainTextToken;
            $succes['name'] = $auth->name;
            $succes['email'] = $auth->email;

            return response()->json([
                'succes'=> true,
                'Note' => 'logout Sukses',
                'data' => $succes
            ]);

        }else{
            return response()->json([
                'succes'=> false,
                'Note' => 'Cek password dan email lagi',
                'data' => null
            ]);
        }
    }
}
