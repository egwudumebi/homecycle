<?php

namespace App\Http\Controllers\Web\Admin\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::query()
            ->with('category')
            ->withCount('listings')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('web.admin.catalogue.subcategories.index', [
            'apiUser' => $request->session()->get('api_user'),
            'subCategories' => $subCategories,
        ]);
    }

    public function show(Request $request, SubCategory $subCategory)
    {
        $subCategory->load('category')->loadCount('listings');

        return view('web.admin.catalogue.subcategories.show', [
            'apiUser' => $request->session()->get('api_user'),
            'subCategory' => $subCategory,
        ]);
    }
}
