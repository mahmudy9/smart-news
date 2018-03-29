<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CArticlesResource;
use App\Http\Resources\KArticlesResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\Search as Ser;
use App\Http\Resources\Profile;
use App\Http\Resources\shares;
use App\Notification;
use App\Role;
use App\About;
use App\Message;
use App\Commentlike;
use App\Bookmark;
use App\Like;
use App\Replylike;
use App\Share;
use App\Report;
use App\Comment;
use App\Reply;
use App\Search;
use App\Keyword;
use App\Category;
use App\User;
use App\Article;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api' , 'User'])->except(['register' , 'login' , 'logout']);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6|max:100',
            'password_c' => 'required|same:password',
            'phone' => 'required|min:9|max:20|unique:users,phone',
            'image' => 'image|max:1999'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors() ] , 400);
        }

        if($request->hasFile('image'))
        {
            $path = $request->file('image')->store('public/storage/files');
            $filename = pathinfo($path , PATHINFO_BASENAME);
        }
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->active= 1;
        if($request->hasFile('image'))
        {
            $user->image = $filename;
        }
        $user->save();
        $success['token'] = $user->createToken('news')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => $success] , 200);
    }



    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->input('email') , 'password' => $request->input('password')]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('news')->accessToken;
            return response()->json(['success' => $success] , 200);
        }
        else
        {
            return response()->json(['error' => 'credentials don\'t match our records'] , 401);
        }
    }



    protected function guard()
    {
        return Auth::guard('api');
    }


    public function logout(Request $request)
    {
        if(!$this->guard()->check())
        {
            return response()->json(['error' => 'no active user found'] , 404);
        }

        $request->user('api')->token()->revoke();

        Auth::guard()->logout();

        Session::flush();

        Session::regenerate();

        return response()->json(['success' => 'user Logged out'] , 200);

    }


    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'old_pass' => 'required|min:6|max:100',
            'new_pass' => 'required|min:6|max:100',
            'c_pass'   => 'required|same:new_pass'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors() ], 400);
        }

        $user = User::find(Auth::id());
        $password = $user->password;
        if(Hash::check($request->old_pass , $password))
        {
            $user->password = Hash::make($request->new_pass);
            $user->save();

            return response()->json(['success' => 'password changed'] , 200);
        }
        else
        {
            return response()->json(['error' => 'wrong pass'],400);
        }
    }


    //articles by category all categories 
    public function index()
    {
        return CArticlesResource::collection(Category::all());
    }

    //articles by category
    public function article_by_category($category)
    {
        return new CArticlesResource(Category::where('name' , $category)->firstOrFail());
    }


    public function article_by_keyword($keyword)
    {
        return KArticlesResource::collection(Keyword::where('name' , $keyword)->get());
    }



    public function keywords()
    {
        $keywords = DB::table('keywords')->get(['name']);
        $keys = [];
        foreach($keywords as $key)
        {
            if(!in_array($key , $keys))
            {
                $keys[] = $key;
            }
            
        }

        return response()->json($keys, 200);
    }


    public function search($search)
    {
        $searchq = trim($search);
        if(strlen($searchq) <= 3)
        {
            return response()->json(['result' => 'no results found'] , 200); 

        }
        else
        {
            $s = new Search;
            $s->user_id = Auth::id();
            $s->search = $searchq;
            $s->save();
            return ArticleResource::collection(Article::where('title' , 'like' , "%$searchq%")->orWhere('body' , 'like' , "%$searchq%")->get());       
        }
    }


    public function mysearchs()
    {
        return new Ser(Search::where('user_id' , Auth::id())->get());
    }


    public function myprofile()
    {
        $user_id = Auth::id();
        return new Profile(User::find($user_id));
    }


    public function comment_article(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'article_id' => 'required|numeric',
            'body' => 'required|min:3|max:255'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()] , 400);
        }

        if(!Article::where('id' , $request->input('article_id'))->exists())
        {
            return response()->json(['error' => 'invalid request'] , 404);
        }
        $article = Article::find($request->input('article_id'));
        $article_name = $article->title;
        $comment = new Comment;
        $comment->article_id = $request->input('article_id');
        $comment->user_id = Auth::id();
        $comment->body = $request->input('body');
        $comment->save();
        $admin = Role::where('name' , 'admin')->first()->users()->first();
        $notify = new Notification;
        $notify->user_id = $admin->id;
        $notify->title = 'New Comment on Article '.$article_name;
        $notify->body = 'Comment: '.$comment->body;
        $notify->save(); 
        return response()->json(['comment' => $comment] , 200);
    }

    public function reply_comment(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'article_id' => 'required|numeric',
            'comment_id' => 'required|numeric',
            'body' => 'required|min:3|max:255'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()] , 400);
        }

        if(!Article::where('id' , $request->input('article_id'))->exists())
        {
            return response()->json(['error' => 'invalid request'] , 404);
        }

        if(!Comment::where('id' , $request->input('comment_id'))->exists())
        {
            return response()->json(['error' => 'invalid request'] , 404);
        }

        $reply = new Reply;
        $reply->article_id = $request->input('article_id');
        $reply->comment_id = $request->input('comment_id');
        $reply->user_id = Auth::id();
        $reply->body = $request->input('body');
        $reply->save();
        $comment = Comment::find($comment_id);
        $notify_user = $comment->user_id;
        $notify = new Notification;
        $notify->user_id = $admin->id;
        $notify->title = 'New Reply on Your Comment '.substr($comment->body , 0 , 10);
        $notify->body = 'Reply: '.$reply->body;
        $notify->save(); 

        return response()->json(['reply' => $reply] , 200);
    }


    public function report_comment(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'comment_id' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()] , 400);
        }

        if(!Comment::where('id' , $request->input('comment_id'))->exists())
        {
            return response()->json(['error' => 'invalid request'] , 404);
        }

        $report = new Report;
        $report->user_id = Auth::id();
        $report->comment_id = $request->input('comment_id');
        $report->save();
        $admin = Role::where('name' , 'admin')->first()->users()->first();
        $admin_id = $admin->id;
        $notify = new Notification;
        $notify->user_id = $admin_id;
        $notify->title = 'New Report on comment '.substr($report->comment->body , 0 ,10);
        $notify->body = 'Comment: '.$report->comment->body;
        $notify->save(); 

        return response()->json(['success' => 'report sent'] , 200);
    }


    public function share_article($article_id)
    {
        if(Share::where(['article_id' => $article_id , 'user_id' => Auth::id()])->exists())
        {
            return response()->json(['error' => 'You can\'t share article twice']);
        }
        if(!Article::find($article_id))
        {
            return response()->json(['error' => 'article doesnt exist']);
        }

        $share = new Share;
        $share->article_id = $article_id;
        $share->user_id = Auth::id();
        $share->save();

        $admin = Role::where('name' , 'admin')->first()->users()->first();
        $admin_id = $admin->id;
        $notify = new Notification;
        $notify->user_id = $admin_id;
        $notify->title = 'New share on article '.substr($share->article->title , 0 ,10);
        $notify->body = 'article: '.substr($share->article->body , 0 , 20);
        $notify->save(); 


        return response()->json(['success' => 'You shared this article'] , 200);
    }



    public function myshares()
    {
        if(Share::where('user_id' , Auth::id())->count() == 0)
        {
            return response()->json(['message' => 'no shares yet'] , 201);
        }

        return shares::collection(Share::where('user_id' , Auth::id())->get());
    }


    public function like_article($article_id)
    {
        if(!Article::find($article_id))
        {
            return response()->json(['error' => 'no such article'] , 404);
        }

        if(Like::where(['user_id' => Auth::id() , 'article_id' => $article_id ])->exists())
        {
            return response()->json(['error' => 'You already liked this article'] , 400);
        }

        $like = new Like;
        $like->user_id = Auth::id();
        $like->article_id = $article_id;
        $like->save();
        return response()->json(['success' => 'You liked this article'] , 200); 
    }


    public function like_comment($comment_id)
    {
        if(!Comment::find($comment_id))
        {
            return response()->json(['error' => 'no such comment'] , 404);
        }

        if(Commentlike::where(['user_id' => Auth::id() , 'comment_id' => $comment_id ])->exists())
        {
            return response()->json(['error' => 'You already liked this comment'] , 400);
        }

        $like = new Commentlike;
        $like->user_id = Auth::id();
        $like->comment_id = $comment_id;
        $like->save();

        $comment= Comment::find($comment_id);
        $notify = new Notification;
        $notify->user_id = $comment->user->id;
        $notify->title = 'New Like on comment '.substr($comment->body , 0 ,10);
        $notify->body = 'user: '.$comment->user->name;
        $notify->save(); 

        return response()->json(['success' => 'You liked this comment'] , 200); 
    }


    public function like_reply($reply_id)
    {
        if(!Reply::find($reply_id))
        {
            return response()->json(['error' => 'no such reply'] , 404);
        }

        if(Replylike::where(['user_id' => Auth::id() , 'reply_id' => $reply_id ])->exists())
        {
            return response()->json(['error' => 'You already liked this reply'] , 400);
        }
        $reply = Reply::find($reply_id);
        $like = new Replylike;
        $like->user_id = Auth::id();
        $like->reply_id = $reply_id;
        $like->save();
        $notify = new Notification;
        $notify->user_id = $reply->user->id;
        $notify->title = 'New Like on reply '.substr($reply->body , 0 ,10);
        $notify->body = 'Reply: '.$reply->body;
        $notify->save(); 

        return response()->json(['success' => 'You liked this reply'] , 200); 
    }


    public function dislike_reply($reply_id)
    {
        if(!Reply::find($reply_id))
        {
            return response()->json(['error' => 'no such reply'] , 404);
        }

        if(!Replylike::where(['user_id' => Auth::id() , 'reply_id' => $reply_id ])->exists())
        {
            return response()->json(['error' => 'You already disliked this reply'] , 400);
        }

        $like = Replylike::where(['user_id' => Auth::id() , 'reply_id' => $reply_id ])->delete();
        return response()->json(['success' => 'You disliked this reply'] , 200); 
    }


    public function dislike_comment($comment_id)
    {
        if(!Comment::find($comment_id))
        {
            return response()->json(['error' => 'no such comment'] , 404);
        }

        if(!Commentlike::where(['user_id' => Auth::id() , 'comment_id' => $comment_id ])->exists())
        {
            return response()->json(['error' => 'You already disliked this comment'] , 400);
        }

        $like = Commentlike::where(['user_id' => Auth::id() , 'comment_id' => $comment_id ])->delete();
        return response()->json(['success' => 'You disliked this comment'] , 200); 
    }



    public function dislike_article($article_id)
    {
        if(!Article::find($article_id))
        {
            return response()->json(['error' => 'no such article'] , 404);
        }

        if(!Like::where(['user_id' => Auth::id() , 'article_id' => $article_id ])->exists())
        {
            return response()->json(['error' => 'You already disliked this article'] , 400);
        }

        $like = Like::where(['user_id' => Auth::id() , 'article_id' => $article_id ])->delete();
        return response()->json(['success' => 'You disliked this article'] , 200); 
    }


    public function bookmark($article_id)
    {
        if(!Article::find($article_id))
        {
            return response()->json(['error' => 'no such article'] , 404);
        }

        if(Bookmark::where(['user_id' => Auth::id() , 'article_id' => $article_id])->exists())
        {
            $bookmark = Bookmark::where(['user_id' => Auth::id() , 'article_id' => $article_id]);

            $bookmark->delete();
            return response()->json(['message' => 'You deleted bookmark'], 200);
        }
        else
        {
            $bookmark = new Bookmark;
            $bookmark->user_id = Auth::id();
            $bookmark->article_id = $article_id;
            $bookmark->save();
            return response()->json(['message' => 'You bookmarked this article'] , 200);
        }
    }


    public function mybookmarks()
    {
        if(Bookmark::where('user_id' , Auth::id())->count() == 0)
        {
            return response()->json(['message' => 'no bookmarks yet' ], 200);
        }

        return shares::collection(Bookmark::where('user_id' , Auth::id())->get());
    }


    public function contact(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'body' => 'required|min:10|max:500'
        ]);
        if($validator->fails())
        {
            return response()->json(['message' => $validator->errors()] , 400);
        }
        $message = new Message;
        $message->user_id = Auth::id();
        $message->body = $request->body;
        $message->save();
        $notify = new Notification;
        $user = Role::where('name' , 'admin')->first()->users()->first();
        $notify->user_id = $user->id;
        $notify->title = 'New Message for you Admin';
        $notify->body = 'Message from '.Auth::user()->name.': '.substr($message->body , 0 , 25);
        $notify->save();
        return response()->json(['message' => 'message saved']);
    }


    public function about()
    {
        return response()->json(['message' => About::firstOrFail()] , 200);
    }


    public function mynotifications()
    {
        $notes = Notification::where('user_id' , Auth::id())->get();
        return response()->json(['notifications' => $notes] , 200);
    }


    public function article($article_id)
    {
        return new ArticleResource(Article::find($article_id));
    }


}
