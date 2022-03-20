<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\LikesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//用戶
Route::post('login',[AuthController::class, 'login']);
Route::post('googlelogin',[AuthController::class, 'googlelogin']);

Route::post('register',[AuthController::class, 'register']);
Route::post('update',[AuthController::class, 'update']);
Route::get('logout',[AuthController::class, 'logout']);

//貼文
Route::post('posts/create',[PostsController::class, 'create']);
Route::post('posts/delete',[PostsController::class, 'delete']);
Route::post('posts/update',[PostsController::class, 'update']);
Route::post('posts',[PostsController::class, 'posts']);
Route::post('posts/search',[PostsController::class, 'search']);

Route::post('user/posts',[PostsController::class, 'userposts']);
Route::post('user/posts/open',[PostsController::class, 'postsopen']);


//留言
Route::post('comments/create',[CommentsController::class, 'create']);
Route::post('comments/delete',[CommentsController::class, 'delete']);
Route::post('comments/update',[CommentsController::class, 'update']);
Route::post('comments/comments',[CommentsController::class, 'comments']);

//按讚
Route::post('posts/like',[LikesController::class, 'like']);
Route::post('posts/cancellike',[LikesController::class, 'cancellike']);

//好友
Route::post('friend',[FriendController::class, 'friend']);
Route::post('friend/check',[FriendController::class, 'check']);
Route::post('friend/invitation',[FriendController::class, 'invitation']);

Route::post('friend/getfriend',[FriendController::class, 'GetFriend']);
Route::post('friend/getfriendinvitation',[FriendController::class, 'GetFriendinvitation']);
Route::post('friend/deletefriendinvitation',[FriendController::class, 'deleteinvitation']);
Route::post('friend/confirmfriendinvitation',[FriendController::class, 'confirminvitation']);


//通知
Route::post('notice',[NotificationController::class, 'notice']);
Route::post('notice/delete',[NotificationController::class, 'notificationdelete']);
Route::post('notice/alldelete',[NotificationController::class, 'notificationAlldelete']);
