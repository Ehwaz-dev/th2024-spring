<?php

namespace App\Http\Controllers\Api;

use App\DTO\ArticleDTO;
use App\DTO\CommentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Article\CreateRequest;
use App\Http\Requests\Api\Article\UpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Event;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected ArticleService $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->middleware('auth:sanctum')->except(['index', 'show', 'comment', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: sorts and выборки
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $dto = new ArticleDTO(
            userId: Auth::id(),
            title: $request->get('name'),
            description: $request->get('description'),
        );

        $article = $this->articleService->create($dto);

        return ArticleResource::make($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $article = Article::query()
            ->with(['owner', 'comments'])
            ->withCount(['likes', 'comments'])
            ->where('slug', $slug);

        return ArticleResource::make($article);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Article $article)
    {

//        if ($article->owner_id != Auth::id()) {
//            abort(403);
//        }

        $dto = new ArticleDTO(
            userId: null,
            title: $request->get('title', $article->title),
            description: $request->get('description', $article->description),
        );

        $this->articleService->update($article, $dto);

        return ArticleResource::make($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {

        if ($article->user_id != Auth::id()) {
            abort(403);
        }

        return response()->json([
            'ok' => $this->articleService->delete($article)
        ]);
    }

    public function comment(Request $request, string $slug)
    {
        $request->validate([
            'comment' => 'required'
        ]);
        $article = Article::idOrSlug($slug)->firstOrFail();

        return response()->json([
            'ok' => true,
            'commented_id' => $article->id,
            'comment'=> $this->articleService->comment($article, new CommentDTO(
                userId: 1,
                comment: $request->get('comment'),
            )),
            'count' => $article->comments()->count()
        ]);
    }

    public function like(Request $request, string $slug)
    {
        $article = Article::idOrSlug($slug)->firstOrFail();
        $this->articleService->like($article, 1);
        return response()->json([
            'ok' => true,
            'liked_id' => $article->id,
            'count' => $article->likes()->count()
        ]);
    }
}
