<?php

namespace App\Http\Controllers;

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
        $products = Product::orderBy($order_column, $order_order)->paginate($size);
        return view('shop', compact('products', 'size', 'order'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $relatedProducts = Product::where('slug', '<>', $product->slug)->get()->take(8);
        return view('details', compact('product', 'relatedProducts'));
    }
}
