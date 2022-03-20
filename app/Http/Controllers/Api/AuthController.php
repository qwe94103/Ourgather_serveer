<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $creds = $request->only(['email','password']);

        if (!$token=auth()->attempt($creds)) {
            return response()->json([
                'success'=>false,
                'massage'=>'invalid credintials'
            ]);
        }
        return response()->json([
            'success'=>true,
            'token'=>$token,
            'user'=>Auth::user()
        ]);
    }

    public function googlelogin(Request $request){
        $users=User::where('googleId',$request->googleId)->first();
        if($users==null){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->googleId = $request->googleId;
            $photo=time().'.jpg';
            $user->photo= $photo;
            $user->save();
            $image=$request->photo;
            Storage::disk('profiles')->put($photo, base64_decode($image));
        }


        $users2=User::where('googleId',$request->googleId)->first();

        return response()->json([
            'success'=>true,
            'user'=>$users2
        ]);
        
    }

    public function register(Request $request){
        $encryptedPass = Hash::make($request->password);

        try{
           
            if ($request->photo!=null) {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = $encryptedPass;
                $photo=time().'.jpg';
                $user->photo= $photo;
                $user->save();
                $image=$request->photo;
                Storage::disk('profiles')->put($photo, base64_decode($image));
                // file_put_contents('storage/profiles/'.$photo,base64_decode($image));
                return $this->login($request);
            }  

        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>''.$request->photo,
            ]);
        }
    }

    public function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success'=>true,
                'message'=>'登出成功'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'massage'=>''.$e,
            ]);
        }
        
    }

    public function update(Request $request){
        $User=User::where("id",$request->id)->first();
        if ($request->photo!="null") {
            Storage::delete('profiles/'.$User->photo);
            $photo=time().'.jpg';
            User::where("id",$request->id)->update([
                "desc"=>$request->desc,
                "name"=>$request->name,
                "photo"=>$photo,
            ]);
            file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
        }
        User::where("id",$request->id)->update([
            "desc"=>$request->desc,
            "name"=>$request->name,
        ]);
        $Users=User::where("id",$request->id)->first();
        return response()->json([
            'success'=>true,
            'user'=>$Users
        ]);
    }
}
