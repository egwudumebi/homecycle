<?php

namespace App\Http\Controllers\Web\Admin\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->withCount(['subCategories', 'listings'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('web.admin.catalogue.categories.index', [
            'apiUser' => $request->session()->get('api_user'),
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, Category $category)
    {
        $category->load([
            'subCategories' => function ($q) {
                $q->withCount('listings')->orderBy('sort_order')->orderBy('name');
            },
        ])->loadCount(['subCategories', 'listings']);

        return view('web.admin.catalogue.categories.show', [
            'apiUser' => $request->session()->get('api_user'),
            'category' => $category,
        ]);
    }
}
