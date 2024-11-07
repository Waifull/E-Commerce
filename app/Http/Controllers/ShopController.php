<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;
        $order_column = "";
        $order_order = "";
        $order = $request->query('order') ? $request->query('order') : -1;
        $filter_brands = $request->query('brands');
        $filter_categories = $request->query('categories');
        switch($order)
        {
            case 1:
                $order_column = "created_at";
                $order_order = "DESC";
                break;
            case 2:
                $order_column = "created_at";
                $order_order = "ASC";
                break;
            case 3:
                $order_column = "sale_price";
                $order_order = "ASC";
                break;
            case 4:
                $order_column = "sale_price";
                $order_order = "DESC";
                break;
            case 5:
                $order_column = "name";
                $order_order = "ASC";
                break;
            case 6:
                $order_column = "name";
                $order_order = "DESC";
                break;
            default:
                $order_column = "id";
                $order_order = "DESC";
        }
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $products = Product::where(function($query) use($filter_brands){
            $query->whereIn('brand_id', explode(',', $filter_brands))->orWhereRaw("'".$filter_brands."'=''");
        })
        ->where(function($query) use($filter_categories){
            $query->whereIn('category_id', explode(',', $filter_categories))->orWhereRaw("'".$filter_categories."'=''");
        })
        ->orderBy($order_column, $order_order)->paginate($size);
        return view('shop', compact('products', 'size', 'order', 'brands', 'categories', 'filter_brands', 'filter_categories'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $relatedProducts = Product::where('slug', '<>', $product->slug)->get()->take(8);
        return view('details', compact('product', 'relatedProducts'));
    }
}
