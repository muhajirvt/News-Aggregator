<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "source_id",
        "author_id",
        "category_id",
        "title",
        "description",
        "published_at",
    ];

    public function scopeWithDetails($query)
    {
        return $query->select(
            'articles.id',
            'articles.source_id',
            'articles.author_id',
            'articles.category_id',
            'articles.title',
            'articles.description',
            'articles.published_at',
            'authors.name as author_name',
            'sources.name as source_name',
            'categories.name as category_name'
        )
        ->join('sources', 'sources.id', '=', 'articles.source_id')
        ->join('authors', 'authors.id', '=', 'articles.author_id')
        ->join('categories', 'categories.id', '=', 'articles.category_id');
    }
}
