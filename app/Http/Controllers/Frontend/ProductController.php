<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Common\ModalProductDetailsResource;
use App\Http\Resources\Frontend\ProductResource;
use App\Http\Resources\Frontend\RelatedProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $id = $request->header('id');
        $user = User::find($id);
        return view('pages.products.products', compact('user'));
    }
    public function get(Request $request)
    {
        $search = $request->query('search');
        // return $request->sub_category_slug !== null ? true : "false";
        $query = Product::query()
            ->where('status', 'publish')
            ->with('brand.offers', 'category.offers', 'sub_category.offers', 'offers', 'reviews');

        $query->when($search !== "null", function ($q) use ($search) {
            return $q->where('name', 'LIKE', '%' . $search . '%');
        });
        $query->when($request->category_slug !== "", function ($q) use ($request) {
            $category = Category::where('slug', $request->category_slug)->first();
            if ($category) {
                return $q->where('category_id', $category->id);
            }
        });
        $query->when($request->sub_category_slug !== "", function ($q) use ($request) {
            $sub_category = SubCategory::where('slug', $request->sub_category_slug)->first();
            if ($sub_category) {
                return $q->where('sub_category_id', $sub_category->id);
            }
        });
        $query->when(!is_null($request->min_price) && !is_null($request->max_price), function ($q) use ($request) {
            if ($request->min_price === "50") {
                return $q->whereBetween('price', [50, $request->max_price]);
            } elseif ($request->min_price === "100") {
                return $q->whereBetween('price', [100, $request->max_price]);
            } elseif ($request->min_price === "200") {
                return $q->whereBetween('price', [200, $request->max_price]);
            } elseif ($request->min_price === "300") {
                return $q->whereBetween('price', [300, $request->max_price]);
            }
        });
        $query->when(!is_null($request->min_price_range) && !is_null($request->max_price_range), function ($q) use ($request) {
            $min_price_range = intval($request->min_price_range);
            return $q->whereBetween('price', [$min_price_range, $request->max_price_range]);
        });

        $products = $query->latest()->get();
        $products_count = Product::where('status', 'publish')->count();

        return ProductResource::collection($products)->additional([
            'products_length' => $products_count
        ]);
    }
    public function modalDetailsShow($id)
    {
        $product = Product::where('status', 'publish')
            ->where('id', $id)
            ->with('collection', 'brand', 'category', 'sub_category', 'product_colors.color', 'product_sizes.size', 'product_size_numbers.size_number', 'product_weights.weight', 'product_images', 'offers', 'brand.offers', 'category.offers', 'sub_category.offers')
            ->first();
        return new ModalProductDetailsResource($product);
    }
    public function productShow($slug)
    {
        $product = Product::with('collection', 'brand', 'category', 'sub_category', 'product_colors.color', 'product_sizes.size', 'product_size_numbers.size_number', 'product_weights.weight', 'product_images', 'offers', 'brand.offers', 'category.offers', 'sub_category.offers', 'reviews.user')
            ->where('slug', $slug)
            ->first();

        $category = Category::where('id', $product->category_id)->first();

        $related_products = $category->products()->get();

        return [
            'product' => new ModalProductDetailsResource($product),
            'related_products' => RelatedProductResource::collection($related_products)
        ];
    }
    public function categoryId(Request $request)
    {
        $sub_category = SubCategory::where('slug', $request->sub_category_slug)->first();
        return $sub_category->category_id;
    }
    public function show($slug): View
    {
        return view('pages.product-details.product-details');
    }
}
