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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $products
     * @return \Illuminate\Http\Response
     */

    public function getProducts()
    {
        $products = Product::withoutTrashed()
            ->orderByDesc('id')
            ->with(['price', 'categories'])
            ->paginate('20');
        return $products;
    }

    public function getListOfDiscountProducts(\Illuminate\Http\Request $request)
    {
//      return $request->input('priceMin');
        if ($request->input('priceMax') ? $maxPrice = $request->input('priceMax') : $maxPrice = '') ;
        if ($request->input('priceMin') ? $minPrice = $request->input('priceMin') : $minPrice = '') ;
        if ($request->input('discount') ? $discount = $request->input('discount') : $discount = '') ;
        if ($request->input('categories') ? $categories = $request->input('categories') : $categories = '') ;

        $productsQuery = Product::query()
            ->orderByDesc('id');


        if (!is_null($discount)) {//products list with discount
            $productsQuery->WhereHas(
                'price', fn($query) => $query
                ->whereNot('discount_percentage', null)
            );
        } else {//products list without discount

            $productsQuery->WhereHas(
                'price', fn($query) => $query
                ->where('discount_percentage', 0)
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

//        $categories filter
        if (!is_null($categories)) {
            $productsQuery->WhereHas('categories', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        }

        $result = $productsQuery
            ->with('price', 'categories')
            ->paginate(20);

        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
