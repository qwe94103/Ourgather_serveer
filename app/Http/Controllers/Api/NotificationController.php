<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\notice;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notice(Request $request){
        $Notices=notice::where('to_user_id',$request->user_id)->get();
        foreach ($Notices as $key => $notice) {
            $notice->User;
        }
        return response()->json([
            'success'=>true,
            'message'=>$Notices,
        ]);
    }
    public function notificationdelete(Request $request){
        $notice=notice::where('id',$request->Notice_id)->delete();
        return response()->json([
            'success'=>true,
            'message'=>$notice,
        ]);
    }
    public function notificationAlldelete(Request $request){
        $notice=notice::where('user_id',$request->user_id)->delete();
        
        return response()->json([
            'success'=>true,
            'message'=>$notice,
        ]);
    }
}
