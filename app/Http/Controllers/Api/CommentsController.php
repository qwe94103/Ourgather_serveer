<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\notice;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function create(Request $request){
        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;
        $comment->comment = $request->desc;
        $comment->save();
        
        $User=User::find($request->user_id);
        $Post=Post::find($request->post_id);
        if ($request->user_id!=$Post->user_id) {
            $notice=new notice([
                'user_id'=>$request->user_id,
                'desc'=>$User->name."在您的貼文留言".$request->desc,
                'to_user_id'=>$Post->user_id,
                'type'=>'comment',
                'url'=>$request->post_id,
                //action=0 ->未讀
                'action'=>0
            ]);
            $notice->save();        
        }
        $commentCount=Comment::where('post_id',$request->post_id)->count();
        Post::where('id',$request->post_id)->update([
            "commentCount"=>$commentCount
        ]);
        return response()->json([
            'success'=>true,
            'massage'=>$comment
        ]);
    }
    public function update(Request $request){
        $comment = Comment::find($request->id);

        if ($comment->user_id != Auth::guard('api')->id()) {
            return response()->json([
                'success'=>false,
                'massage'=>'身分不符合'
            ]);
        }

        $comment->comment = $request->comment;
        $comment->update();

        return response()->json([
            'success'=>true,
            'massage'=>'更新成功'
        ]);
    }
    public function delete(Request $request){
        $comment = Comment::find($request->id);

        if ($comment->user_id!=Auth::guard('api')->id()) {
            return response()->json([
                'success'=>false,
                'message'=>'身分不符合'
            ]);
        }

        $comment->delete();

        return response()->json([
            'success'=>true,
            'message'=>'刪除成功'
        ]);
    }
    public function comments(Request $request){
        $comments = Comment::where('post_id',$request->post_id)->get();

        foreach ($comments as $comment) {
            $comment->user;
        }

        return response()->json([
            'success'=>true,
            'message'=>$comments
        ]);
    }
    
}
