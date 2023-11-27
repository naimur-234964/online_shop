<?php

use App\Models\Category;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->where('showHome', 'Yes')
        ->get();
}
