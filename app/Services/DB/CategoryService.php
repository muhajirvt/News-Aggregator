<?php

namespace App\Services\DB;
use App\Models\Category;

class CategoryService
{
    public function upsertCategories($categoryNames)
    {
        $existing = Category::select("id","name")->whereIn("name", $categoryNames)->get();

        $existingNames = $existing->pluck('name');

        $newCategories = $categoryNames
            ->diff($existingNames)
            ->map(fn($name) => ['name' => $name])
            ->all();

        if (!empty($newCategories)) {
            Category::insert($newCategories);
        }

        return Category::select("id","name")->whereIn("name", $categoryNames)->get()->pluck("id","name");
    }
}
