<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\friend as ModelsFriend;
use App\Models\friend_check;
use App\Models\notice;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Stmt\Foreach_;

class FriendController extends Controller
{
// 好友主頁
    public function friend(Request $request){
        $User=User::where("id",$request->to_user_id)->first();
        $posts=Post::orderBy('id','desc')->where('user_id',$request->to_user_id)->get();

        return response()->json([
            'success'=>true,
            'message'=>$User,
            'posts'=>$posts
        ]);
    }
//檢查是否有好友邀請
    public function check(Request $request){
        $Friend=friend_check::where('user_id',$request->user_id)->where('to_user_id',$request->to_user_id)->first();
        $Friend2=friend_check::where('to_user_id',$request->user_id)->where('user_id',$request->to_user_id)->first();
        if ($Friend!=null||$Friend2!=null) {
            if ($Friend!=null) {
                if ($Friend->action==0) {
                    return response()->json([
                        'success'=>true,
                        'isFriend'=>false,
                        'message'=>$Friend,
                    ]);
                }else{
                    return response()->json([
                        'isFriend'=>true,
                        'success'=>false,
                        'message'=>$Friend,
                    ]);
                }
            }else{
                if ($Friend2->action==0) {
                    return response()->json([
                        'isFriend'=>false,
                        'success'=>true,
                        'message'=>$Friend2,
                    ]);
                }else{
                    return response()->json([
                        'isFriend'=>true,
                        'success'=>false,
                        'message'=>$Friend2,
                    ]);
                }
            }
        }
        return response()->json([
            'success'=>false,
        ]);
    }
//送出好友邀請
    public function invitation(Request $request){
        $Friend=new friend_check([
            'user_id'=>$request->user_id,
            'to_user_id'=>$request->to_user_id,
            'action'=>0
        ]);
        $Friend->save();

        $User=User::where('id',$request->user_id)->first();
        $Friend=new notice([
            'user_id'=>$request->user_id,
            'desc'=>"使用者'".$User->name."'發送好友邀請給您了",
            'to_user_id'=>$request->to_user_id,
            'type'=>'friend_invitation',
            'url'=>$request->user_id,
            //action=0 ->未讀
            'action'=>0
        ]);
        $Friend->save();

        $Friend->save();
        return response()->json([
            'success'=>true,
            'message'=>$Friend,
        ]);
    }
//取得全部好友列表
    public function GetFriend(Request $request){
        $GetFriends=ModelsFriend::where('user_id',$request->user_id)->get();
        $GetFriends2=ModelsFriend::where('to_user_id',$request->user_id)->get();

        foreach ($GetFriends as $key => $GetFriend) {
            $GetFriend->touser;
        }
        foreach ($GetFriends2 as $key => $GetFriend2) {
            $GetFriend2->user;
        }

        return response()->json([
            'success'=>true,
            'message'=>$GetFriends,
            'message2'=>$GetFriends2
        ]);
 
    }
//取得全部好友邀請列表
    public function GetFriendinvitation(Request $request){
        $GetFriends=friend_check::where('to_user_id',$request->user_id)->where('action',0)->get();
        foreach ($GetFriends as $key => $GetFriend) {
            $GetFriend->user;
        }
        return response()->json([
            'success'=>true,
            'message'=>$GetFriends
        ]);

    }
//同意好友邀請
    public function confirminvitation(Request $request){
        $invitation=friend_check::where('to_user_id',$request->user_id)->where('user_id',$request->to_user_id)->first();

        $invitation->action=1;
        $invitation->save();

        $Friend=new ModelsFriend([
            'user_id'=>$request->to_user_id,
            'to_user_id'=>$request->user_id
        ]);
        $Friend->save();

        return response()->json([
            'success'=>true,
        ]);
    }
//刪除好友邀請
    public function deleteinvitation(Request $request){
        friend_check::where('user_id',$request->to_user_id)->where('to_user_id',$request->user_id)->delete();
        return response()->json([
            'success'=>true,
        ]);
    }

}
