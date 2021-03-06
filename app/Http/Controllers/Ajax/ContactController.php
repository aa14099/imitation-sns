<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use App\User;
use App\Reply;

class ContactController extends Controller
{
    public function followFunction(Request $request){
      $user_id=$request->input('user_id');
      $own=\Auth::user();
      $user=User::find($user_id);

      $follows=unserialize($own->follow); //自分がフォロー中のユーザーのid
      $followers=unserialize($user->follower); //相手をフォローしているユーザーのid

      if(isset($follows["$user_id"])){
        unset($follows["$user_id"]);
        unset($followers["$own->id"]);
      }else{
        $follows["$user_id"]=$user_id;
        $followers["$own->id"]=$own->id;
      }

      $own->follow=serialize($follows);
      $own->save();
      $user->follower=serialize($followers);
      $user->save();

      return response()->json([
            'result' => true
        ]);

    }


    public function goodFunction(Request $request){
      $article_id=$request->input('article_id');
      $own=\Auth::user();
      $article=Article::find($article_id);

      $goods=unserialize($article->good);
      if(isset($goods["$own->id"])){
        //いいねしていたらいいねを取り消す
        unset($goods["$own->id"]);
      }else{
        //いいねしていなかったらいいねする
        $goods["$own->id"]=$own->id;
      }
      $article->good=serialize($goods);
      $article->save();

      $goodCount=count($goods);

      return response()->json([
            'result' => $goodCount
        ]);
    }


    public function goodCount(Request $request){
      $article_id=$request->input('article_id');
      $article=Article::find($article_id);

      $goodCount=count(unserialize($article->good));
      return response()->json([
            'result' => $goodCount
      ]);
    }



    public function replyGoodFunction(Request $request)
    {
      $reply_id=$request->input('reply_id');
      $own=\Auth::user();
      $reply=Reply::find($reply_id);

      $goods=unserialize($reply->good);
      if(isset($goods["$own->id"])){
        //いいねしていたらいいねを取り消す
        unset($goods["$own->id"]);
      }else{
        //いいねしていなかったらいいねする
        $goods["$own->id"]=$own->id;
      }
      $reply->good=serialize($goods);
      $reply->save();

      $goodCount=count($goods);

      return response()->json([
            'result' => $goodCount
        ]);
    }


    public function replyGoodCount(Request $request)
    {
      $reply_id=$request->input('reply_id');
      $reply=Reply::find($reply_id);

      $goodCount=count(unserialize($reply->good));
      return response()->json([
            'result' => $goodCount
      ]);
    }
}
