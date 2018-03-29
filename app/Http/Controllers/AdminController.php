<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;
use App\User;
use App\Article;
use App\Comment;
Use App\Share;
use App\Message;
use App\Notification;
use App\Report;
use App\Keyword;
use App\Commentlike;
use App\Like;
use App\Reply;
use App\Replylike;
use App\Search;

class AdminController extends Controller
{


    public function __construct()
    {
        $this->middleware(['auth' , 'Admin']);
    }


    public function index()
    {
        return view('admin.index');
    }


    public function create_article()
    {
        $cats = Category::all();
        return view('admin.createarticle' , compact('cats'));
    }


    public function store_article(Request $request)
    {
        $vlidator = Validator::make($request->all() , [
            'title' => 'required|min:10||max:255',
            'file' => 'file',
            'body' => 'required|min:100',
            'category' => 'required',
            'key1' => 'required|min:3|max:14',
            'key2' => 'required|min:3|max:14'
        ]);

        if($validator->fails())
        {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        $article = new Article;
        $article->title = $request->input('title');

        if($request->hasFile('file'))
        {
            $path = $request->file('file')->store('public/storage/files');
            $file = pathinfo($path , PATHINFO_BASENAME);
            $article->file = $file;
        }
        $article->body = $request->input('body');
        $article->category_id = $request->input('category');
        $article->save();

        $key1 = new Keyword;
        $key1->article_id = $article->id;
        $key1->name = $request->input('key1');
        $key1->save();
        $key2 = new Keyword;
        $key2->article_id = $article->id;
        $key2->name = $request->input('key2');
        $key2->save();
        $request->session()->flash('status' , 'Article saved');
        return redirect()->back();
    }



    public function edit_article($id)
    {
        $article = Article::find($id);
        $keys = Keyword::where('article_id' , $id)->get();
        return view('admin.editarticle' , compact($article , $keys));
    }


    public function update_article(Request $request)
    {
        $vlidator = Validator::make($request->all() , [
            'title' => 'required|min:10||max:255',
            'file' => 'file',
            'body' => 'required|min:100',
            'category' => 'required',
            'key1' => 'required|min:3|max:14',
            'key2' => 'required|min:3|max:14'
        ]);

        if($validator->fails())
        {
            return redirect()->back()
            ->withErrors($validator);
        }
        $article = Article::find($request->input('article_id'));
        $article->title = $request->input('title');

        if($request->hasFile('file'))
        {
            $path = $request->file('file')->store('public/storage/files');
            $file = pathinfo($path , PATHINFO_BASENAME);
            $article->file = $file;
        }
        $article->body = $request->input('body');
        $article->category_id = $request->input('category');
        $article->save();

        $key1 = Keyword::find($request->input('key1_id'));
        $key1->name = $request->input('key1');
        $key1->save();
        $key2 = Keyword::find($request->input('key2_id'));
        $key2->name = $request->input('key2');
        $key2->save();
        $request->session()->flash('status' , 'Article updated');
        return redirect()->back();

    }


    public function delete_article($id)
    {
        if(!Article::find($id))
        {
            return response()->json(['message' => 'Article not found'] , 404);
        }

        $article = Article::find($id);
        $article->delete();
        $keys = Keyword::where('article_id' , $id);
        $keys->delete();
        $bookamrks = Bookmark::where('article_id' , $id);
        $bookmarks->delete();
        $likes = Like::where('article_id' , $id);
        $likes->delete();
        $shares = Share::where('article_id' , $id);
        $shares->delete();
        $comments = Comment::where('article_id' , $id);
        $comments->delete();
        $replies = Reply::where('article_id' , $id);
        $replies->delete();
        return response()->json(['success' => 'article and its relations deleted'] , 200);
    }


    public function categories()
    {
        //this function have create category in its view
        $cats = Category::all();
        return view('admin.categories' , compact('cats'));
    }

    public function store_category(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|min:3|max:20'
        ]);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cat = new Category;
        $cat->name = $request->input('name');
        $cat->save();
        $request->session()->flash('status' , 'You created category');
        return redirect()->back();
    }

    public function delete_category($id)
    {
        if(!Category::find($id))
        {
            return response()->json(['message' => 'Category doesnt exist'] , 404);
        }

        if(Article::where('category_id' , $id)->exists())
        {
            return response()->json(['message' => 'sorry you cant delete it has relations ,change articles with this category first and come back to delete category'],400);
        }

        $cat = Category::find($id);
        $cat->delete();

    }


    public function users_list()
    {
        $users = User::paginate(20);
        return view('admin.userslist' , compact('users'));
    }


    public function delete_user($id)
    {
        if(!User::find($id))
        {
            return response()->json(['message' => 'sorry user doesnt exist'] , 404);
        }
        $shares = Share::where('user_id' , $id);
        $shares->delete();
        $searches = Search::where('user_id' , $id);
        $searches->delete();
        $reports = Report::where('user_id' , $id);
        $reports->delete();
        $replies = Reply::where('user_id' , $id);
        $replies->delete();
        $replylikes = Replylike::where('user_id' , $id);
        $replylikes->delete();
        $comments = Comment::where('user_id' , $id);
        $comments->delete();
        $notifications = Notification::where('user_id' , $id);
        $notifications->delete();
        $bookmarks = Bookmark::where('user_id' , $id);
        $bookmarks->delete();
        $commentlikes = Commentlike::where('user_id' , $id);
        $commentlikes->delete();
        $messages = Message::where('user_id' , $id);
        $messages->delete();
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'user deleted successfully'] , 200);
    }


    public function view_reports()
    {
        $reports = Report::paginate(20);
        return view('admin.reports' , compact('reports'));
    }


    public function view_comments()
    {
        $comments = Comment::paginate(20);
        return view('admin.comments' , compact('comments'));
    }


    public function delete_report($id)
    {
        if(!Report::find($id))
        {
            return response()->json(['message' => 'not found'] , 404);
        }
        $report = Report::find($id);
        $report->delete();
        return response()->json(['message' => 'report deleted'] , 200);
    }


    public function delete_comment($id)
    {
        if(!Comment::find($id))
        {
            return response()->json(['message' => 'not found'] , 404);
        }
        $reports = Report::where('comment_id' , $id);
        $reports->delete();
        $replies = Reply::where('comment_id' , $id);
        $replies->delete();
        $commentlikes = Commentlike::where('comment_id' , $id);
        $commentlikes->delete();
        $comment = Comment::find($id);
        $comment->delete();
        return response()->json(['message' => 'comment deleted'] , 200);
    }


    public function vew_replies()
    {
        $replies = Reply::paginate(20);
        return view('admin.replylist' , compact('replies'));
    }


    public function delete_replies($id)
    {
        if(!Reply::find($id))
        {
            return response()->json(['message' => 'not found'] , 404);
        }

        $replylikes = Replylike::where('reply_id' , $id);
        $replylikes->delete();
        $reply = Reply::find($id);
        $reply->delete();
        return response()->json(['message' => 'reply deleted'] ,200);
    }


    public function view_messages()
    {
        $messages = Message::paginate(20);
        return view('admin.messages' , compact('messages'));
    }


    public function delete_message($id)
    {
        if(!Message::find($id))
        {
            return response()->json(['message' => 'cant find message'] , 404);
        }

        $message = Message::find($id);
        $message->delete();
        return response()->json(['message' => 'deleted'] , 200);
    }

    public function notifications()
    {
        $nots = Notification::paginate(20);
        return view('admin.nots' , compact('nots'));
    }

    public function mynotifications()
    {
        $nots = Notification::where('user_id' , Auth::id())->paginate(20);
        return view('admin.mynots' , compact('nots'));
    }

}
