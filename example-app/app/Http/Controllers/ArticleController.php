<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\DeleteArticleRequest;
use App\Http\Requests\EditArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->except(['index', 'show']);
    }
    public function create()
    {
        return view('articles/create');
    }

    public function store(CreateArticleRequest $request)
    {
        // 비어있지않고, 문자열이고, 255자를 넘으면 안된다.
        $input = $request->validated();
        // 비어있지않고, 문자열이고, 255자를 넘으면 안된다.
        // $input = $request->validate([
        //     'body' => [
        //         'required',
        //         'string',
        //         'max:255'
        //     ],
        // ]);

        // //글을 저장한다.

        // //PDO를 이용하는 방법
        // $host = config('database.connections.mysql.host');
        // $dbname = config("database.connections.mysql.database");
        // $username = config('database.connections.mysql.username');
        // $password = config("database.connections.mysql.password");
        // // pdo 객체를 만들고
        // $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // // 쿼리 준비
        // $stmt = $conn->prepare("INSERT INTO articles (body, user_id) VALUES (:body, :userId)");

        // // dd($request->all());
        // // dd($request->collect());
        // // $body = $request->input('body');
        // // 쿼리 값을 설정
        // $stmt->bindValue(':body', $input['body']);
        // // $stmt->bindValue(':userId', $request->user()->id);
        // $stmt->bindValue(':userId', Auth::id());
        // // 실행
        // $stmt->execute();

        // // DB 파사드를 이용하는 방법
        // DB::statement("INSERT INTO articles (body, user_id) VALUES (:body, :userId)", ['body' => $input['body'], 'userId' => Auth::id()]);

        // // 쿼리 빌더를 사용하는 방법
        // DB::table('articles')->insert([
        //     'body' => $input['body'],
        //     'user_id' => Auth::id()
        // ]);

        // // 엘로퀀트 ORM을 사용한는 방법
        // $article = new Article;
        // $article->body = $input['body'];
        // $article->user_id = Auth::id();
        // $article->save();

        // // 엘로퀀트 ORM을 사용한는 방법
        // Article::create([
        //     'body' => $input['body'],
        //     'user_id' => Auth::id()
        // ]);

        // 엘로퀀트 ORM을 사용한는 방법
        Article::create([
            'body' => $input['body'],
            'user_id' => Auth::id()
        ]);
        return redirect()->route('articles.index');
    }

    public function index(Request $request)
    {
        // $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 3);
        // $skip = ($page -1) * $perPage;

        $articles = Article::with('user')
            ->select('id', 'body', 'user_id', 'created_at')
            // ->skip($skip)
            // ->take($perPage)
            ->latest()
            ->paginate($perPage);
        // ->orderby('created_at', 'desc')
        // ->oldest()
        // ->orderby('body', 'asc')
        // ->get();
        // $articles->withQueryString();
        // $articles->appends(['filter' => 'name']);
        // $totalCount = Article::count();
        // $now = Carbon::now();
        // $past = clone $now;
        // $past->subHours(3);
        // dd($now->diffInMinutes($past));
        // $results = DB::table('articles as a')
        //     ->join('users as u', 'a.user_id', '=', 'u.id')
        //     ->select(['a.*', 'u.name'])
        //     ->latest()
        //     ->paginate($perPage);
        return view(
            'articles.index',
            [
                'articles' => $articles,
                // 'results' => $results
                // 'totalCount' => $totalCount,
                // 'page' => $page,
                // 'perPage'=>$perPage
            ]
        );
        // return view('articles.index')->with('articles', $articles);
    }

    // Route::get('articles/{id}', function($id) {
    //     $article = Article::find($id);

    //     return view('articles.show', ['article' => $article]);
    // });
    // 위를 라우트 모델 바인딩을 적용하면
    public function show(Article $article)
    {
        $article->load('comments.user');
        return view('articles.show', ['article' => $article]);
    }

    public function edit(EditArticleRequest $request, Article $article)
    {
        // 컨트롤러 헬퍼를 사용
        // $this->authorize('edit', $article);
        return view('articles.edit', ['article' => $article]);
    }

    public function update(Article $article, UpdateArticleRequest $request)
    {
        // if (!Auth::user()->can('update', $article)) {
        //     abort(403);
        // };

        // 컨트롤러 헬퍼를 사용
        // $this->authorize('update', $article);

        $input = $request->validated();

        $article->body = $input['body'];
        $article->save();

        return redirect()->route('articles.index');
    }

    public function destroy(DeleteArticleRequest $request, Article $article)
    {
        // 컨트롤러 헬퍼를 사용
        // $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index');
    }
}
