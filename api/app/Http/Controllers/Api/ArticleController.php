<?php

namespace App\Http\Controllers\Api;

use App\DTO\ArticleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Article\CreateRequest;
use App\Http\Requests\Api\Article\UpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected ArticleService $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->middleware('auth:sanctum')->except(['index', 'show']);
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

        if ($article->owner_id != Auth::id()) {
            abort(403);
        }

        $dto = new ArticleDTO(
            userId: null,
            title: $request->get('name'),
            description: $request->get('description'),
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
}
