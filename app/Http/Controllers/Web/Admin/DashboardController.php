<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\SavedListing;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function overview(Request $request)
    {
        $totalListings = Listing::query()->count();
        $activeListings = Listing::query()->where('status', 'active')->count();
        $soldListings = Listing::query()->where('status', 'sold')->count();
        $hiddenListings = Listing::query()->where('status', 'hidden')->count();
        $featuredListings = Listing::query()->where('is_featured', true)->count();

        $soldAmount = (float) Listing::query()->where('status', 'sold')->sum('price');

        $users = User::query()->count();
        $categories = Category::query()->count();
        $subCategories = SubCategory::query()->count();
        $saved = SavedListing::query()->count();

        $salesRate = $totalListings > 0 ? round(($soldListings / $totalListings) * 100, 1) : 0.0;
        $featuredRate = $totalListings > 0 ? round(($featuredListings / $totalListings) * 100, 1) : 0.0;

        $days = 14;
        $start = Carbon::now()->subDays($days - 1)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $rows = Listing::query()
            ->whereNotNull('published_at')
            ->whereBetween('published_at', [$start, $end])
            ->selectRaw('DATE(published_at) as day, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(published_at)'))
            ->orderBy(DB::raw('DATE(published_at)'))
            ->pluck('total', 'day');

        $publishedSeries = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $publishedSeries[] = [
                'day' => $day,
                'total' => (int) ($rows[$day] ?? 0),
            ];
        }

        $maxPublished = max(1, ...array_map(fn ($x) => (int) ($x['total'] ?? 0), $publishedSeries));

        $latestListings = Listing::query()
            ->with(['city', 'state'])
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('web.admin.dashboard.overview_single', [
            'apiUser' => $request->session()->get('api_user'),
            'stats' => [
                'total_listings' => $totalListings,
                'active_listings' => $activeListings,
                'sold_listings' => $soldListings,
                'hidden_listings' => $hiddenListings,
                'featured_listings' => $featuredListings,
                'sold_amount' => $soldAmount,
                'users' => $users,
                'categories' => $categories,
                'sub_categories' => $subCategories,
                'saved' => $saved,
                'sales_rate' => $salesRate,
                'featured_rate' => $featuredRate,
            ],
            'publishedSeries' => $publishedSeries,
            'maxPublished' => $maxPublished,
            'latestListings' => $latestListings,
        ]);
    }

    public function sales(Request $request)
    {
        return view('web.admin.dashboard.sales_single', [
            'apiUser' => $request->session()->get('api_user'),
        ]);
    }

    public function orders(Request $request)
    {
        return view('web.admin.dashboard.orders_single', [
            'apiUser' => $request->session()->get('api_user'),
        ]);
    }

    public function deliveries(Request $request)
    {
        return view('web.admin.dashboard.deliveries_single', [
            'apiUser' => $request->session()->get('api_user'),
        ]);
    }

    public function saved(Request $request)
    {
        return view('web.admin.dashboard.saved_single', [
            'apiUser' => $request->session()->get('api_user'),
        ]);
    }
}
