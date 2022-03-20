<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Constraint\Count;

class PostsController extends Controller
{
    public function create(Request $request){
        $post = new Post();
        $post->user_id = $request->user_id;
        $post->desc = $request->desc;
        $post->photo = $request->photo;

        if ($request->photo != null) {
            $photo = time().'.jpg';
            Storage::disk('posts')->put($photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;
        return response()->json([
            'success'=>true,
            'message'=>'成功',
            'post'=>$post
        ]);
        
    }
    
    public function update(Request $request){
        $post = Post::find($request->id);
         if (Auth::guard('api')->id()!=$post->user_id) {
             return response()->json([
                 'success'=>false,
                 'massage'=>'未符合身分'
             ]);
         }

         $post->desc = $request->desc;
         $post->update();
         return response()->json([
             'success'=>true,
             'message'=>'更新成功'
         ]);
    }
    
    public function delete(Request $request){
        $post = Post::find($request->id);
         if (Auth::guard('api')->id()!=$post->user_id) {
             return response()->json([
                 'success'=>false,
                 'massage'=>'未符合身分'
             ]);
         }

         if ($post->photo != '') {
             Storage::delete('public/posts/'.$post->photo);
         }
         $post->delete();
         return response()->json([
             'success'=>true,
             'message'=>'刪除成功'
         ]);
    }

    public function posts(Request $request){
        $posts=Post::orderBy('id','desc')->get();
        foreach($posts as $post){
            $Like=Like::where("user_id",$request->id)->where("post_id",$post->id)->first();
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likeCount'] = Count($post->likes);
            $post['selfLike'] = false;
            if ($Like!=null) {
                $post['selfLike'] = true;
            }
        }
        
        return response()->json([
            'success'=>true,
            'message'=>$posts
        ]);
    }

    public function userposts(Request $request){
        $posts=Post::orderBy('id','desc')->where('user_id',$request->user_id)->get();

        return response()->json([
            'success'=>true,
            'massage'=>$posts
        ]);
    }

    public function postsopen(Request $request){
        $posts=Post::where("id",$request->id)->get();
        $Like = Like::where('post_id',$request->id)->where('user_id',$request->user_id)->first();
        foreach ($posts as $key => $value) {
            $value['selfLike'] = false;
            $value->user;
            if($Like!=null){
                $value['selfLike'] = true;
            }
        }
        return response()->json([
            'success'=>true,
            'message'=>$posts
        ]);
    }


    public function search(Request $request){
        if($request->Type=="post"){
            if ($request->desc!=null) {
                $posts=Post::where('desc','LIKE', '%'.$request->desc.'%')->get();
                foreach($posts as $post){
                    $post->user;
                    $post['commentsCount'] = count($post->comments);
                    $post['likeCount'] = Count($post->likes);
                    $post['selfLike'] = false;
                    foreach ($post->likes as $like) {
                        if ($like->user_id == $request->user_id) {
                            $post['selfLike'] = true;
                        }
                    }
                }
                return response()->json([
                    'success'=>true,
                    'message'=>$posts
                ]);
            }
            return response()->json([
                'success'=>false,
            ]);
        }elseif($request->Type=="user"){
            if ($request->desc!=null) {
                $users=User::where('name','LIKE', '%'.$request->desc.'%')->get();
                return response()->json([
                    'success'=>true,
                    'message'=>$users
                ]);
            }
            return response()->json([
                'success'=>false,
            ]);
        }
    }

    
        
}
