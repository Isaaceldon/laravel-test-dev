<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * Registration
     */
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(),User::$register_rules);

        if($validator->fails()){
            return response(
                $validator->errors(),Response::HTTP_BAD_REQUEST
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('user-token')->accessToken;

        return response()->json(['token' => $token,'user' => $user,], 200);

    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $user = User::where('email', $request->email)->first();
            $token = auth()->user()->createToken('user-token')->accessToken;
            return response()->json(['token' => $token,"user"=>$user], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
