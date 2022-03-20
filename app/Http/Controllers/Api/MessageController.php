<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message as ModelsMessage;
use Illuminate\Http\Request;
class MessageController extends Controller
{
    public function Message(Request $request){
        $Message=ModelsMessage::where('user_id',$request->user_id)->get();
        return response()->json([
            'success'=>true,
            'message'=>$Message,
        ]);
    }

    public function DelMessage(Request $request){

    }

    public function FriendMessage(Request $request){
        
    }

}
