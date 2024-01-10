<?php

use App\Models\Category;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->where('showHome', 'Yes')
        ->orderBy('id', 'DESC')
        ->where('status', 1)
        ->with('sub_category')
        ->get();
}
