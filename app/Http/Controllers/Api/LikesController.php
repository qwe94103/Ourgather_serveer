<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\notice;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function like(Request $request){
        $likes=Like::where('post_id',$request->post_id)->where('user_id',$request->user_id)->first();
        $Notice=notice::where('user_id',$request->user_id)->where('type','post')->where('url',$request->post_id)->first();
        if ($likes!=null) {
            $likes->delete();
            if ($Notice!=null) {
                $Notice->delete();
            }
            $likeCount=Like::where("post_id",$request->post_id)->count();
            Post::where('id',$request->post_id)->update([
                'likeCount'=>$likeCount
            ]);
            return response()->json([
                'success'=>false,
                'message'=>$likeCount
            ]);
        }else{
            $like= new Like();
            $like->user_id = $request->user_id;
            $like->post_id = $request->post_id;
            $like->save();
            $likeCount=Like::where("post_id",$request->post_id)->count();
            Post::where('id',$request->post_id)->update([
                'likeCount'=>$likeCount
            ]);

            $post=post::where('id',$request->post_id)->first();
            $User=User::where('id',$request->user_id)->first();

            if ($request->user_id!=$post->user_id) {
                $notice=new notice([
                    'user_id'=>$request->user_id,
                    'desc'=>$User->name."對您的貼文按讚<3",
                    'to_user_id'=>$post->user_id,
                    'url'=>$request->post_id,
                    'type'=>'post',
                    //action=0 ->未讀
                    'action'=>0
                ]);
                $notice->save();
            }
            return response()->json([
                'success'=>true,
                'message'=>$likeCount
            ]);
        }
    }


}
