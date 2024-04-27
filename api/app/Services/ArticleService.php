<?php

namespace App\Services;

use App\DTO\ArticleDTO;
use App\Models\Article;
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
}
