<?php

namespace App\Services\DB;

use App\Models\Author;

class AuthorService
{
    public function upsertAuthors($authorNames)
    {
        $existing = Author::select("id","name")->whereIn("name", $authorNames)->get();

        $existingNames = $existing->pluck('name');

        $newAuthors = $authorNames
        ->diff($existingNames)
        ->map(fn($name) => ['name' => $name])
        ->all();

        if (!empty($newAuthors)) {
            Author::insert($newAuthors);
        }
        return Author::select("id","name")->whereIn("name", $authorNames)->get()->pluck("id","name");
    }
}

