<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SubClass\Price;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Symfony\Component\Console\Input\Input;

class ProductController extends Controller
{

    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->user_id = Auth::id();

        try {
            $product->save();
            $product->categories()->attach($request->categories);
        } catch (Exception $exception) {
            return redirect()->back()->with($exception);
        }
        $disount = new SubClass\Discount();
        $discount = $disount->discountChecker($request->sku, $request->categories);

        $price = new Price();
        $price->finalPrice($request->price, $discount, $product->id);

        return 'Done';

    }


    public function getListOfProducts(\Illuminate\Http\Request $request)
    {

        if ($request->input('priceMax') ? $maxPrice = $request->input('priceMax') : $maxPrice = null) ;
        if ($request->input('priceMin') ? $minPrice = $request->input('priceMin') : $minPrice = null) ;
        if ($request->input('discount') == true ? $discount = $request->input('discount') : $discount = null) ;
        if ($request->input('categories') ? $categories = $request->input('categories') : $categories = null) ;

        $productsQuery = Product::query()
            ->orderByDesc('id');


        if (!is_null($discount)) {//******* products list with discount
            $productsQuery->WhereHas(
                'price', fn($query) => $query
                ->whereNot('discount_percentage', null)
            );
        } else {//********* products list without discount

            $productsQuery->WhereHas(
                'price', fn($query) => $query
                ->where('discount_percentage', null)
            );

            //*******$maxPrice filter
            if (!is_null($maxPrice)) {
                $productsQuery->WhereHas('price', function ($query) use ($maxPrice) {
                    $query->where('orginal', '<', $maxPrice);
                });
            }

            //*******$minPrice filter
            if (!is_null($minPrice)) {
                $productsQuery->WhereHas('price', function ($query) use ($minPrice) {
                    $query->where('orginal', '>', $minPrice);
                });
            }

        }

//   **************     $categories filter
        if (!is_null($categories)) {
            $productsQuery->WhereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }

        $result = $productsQuery
            ->with('price', 'categories')
            ->paginate(20);

        return $result;

    }

}
