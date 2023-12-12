<?php

use App\Http\Controllers\ProfileController;
use App\Models\Article;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/articles/create', function () {
    return view('articles/create');
})->name('articles.create');

Route::post('/articles', function (Request $request) {
    // 비어있지않고, 문자열이고, 255자를 넘으면 안된다.
    $input = $request->validate([
        'body' => [
            'required',
            'string',
            'max:255'
        ],
    ]);

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
})->name('articles.store');

Route::get('articles', function (Request $request) {
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
})->name('articles.index');

// Route::get('articles/{id}', function($id) {
//     $article = Article::find($id);
    
//     return view('articles.show', ['article' => $article]);
// });
// 위를 라우트 모델 바인딩을 적용하면
Route::get('articles/{article}', function(Article $article) {
    
    return view('articles.show', ['article' => $article]);
})->name('articles.show');

Route::get('articles/{article}/edit', function(Article $article) {
    
    return view('articles.edit', ['article' => $article]);
})->name('articles.edit');

Route::patch('articles/{article}', function(Article $article, Request $request) {
    $input = $request->validate([
        'body' => [
            'required',
            'string',
            'max:255'
        ],
    ]);

    $article->body = $input['body'];
    $article->save();

    return redirect()->route('articles.index');
})->name('articles.update');

Route::delete('articles/{article}', function(Article $article) {
    $article->delete();

    return redirect()->route('articles.index');
})->name('articles.delete');