<?php

namespace App\Services;

use App\DTO\ArticleDTO;
use App\DTO\CommentDTO;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Str;

class ArticleService
{
    public function create(ArticleDTO $dto): Article
    {
        return Article::create([
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'slug' => Str::slug($dto->userId . $dto->title),
            'description' => $dto->description,
        ]);
    }

    public function update(Article $article, ArticleDTO $dto): void
    {
        $article->update([
            'title' => $dto->title,
            'description' => $dto->description,
        ]);
    }

    public function delete(Article $article): ?bool
    {
        return $article->delete();
    }

    public function comment(Article $article, CommentDTO $dto): Comment
    {
        return $article->comments()->create([
            'user_id' => $dto->userId,
            'comment' => $dto->comment,
        ]);
    }

    public function like(Article $article, int $userId)
    {
        $l = $article->likes()->where(['user_id' => $userId])->first();
        if (isset($l)) {
            $l->delete();
            return;
        }

        $article->likes()->create(['user_id' => $userId]);
    }

}
