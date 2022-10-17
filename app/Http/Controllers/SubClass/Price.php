<?php

namespace App\Http\Controllers\SubClass;

use Mockery\Exception;

class Price
{

    public function finalPrice($price, $discount, $productId)
    {
        if ($discount == 0) {
            self::priceWithoutDiscount($price, $productId);
            return 'ok';
        }
        self::priceWithDiscount($price, $discount, $productId);
        return 'ok';
    }

    private function priceWithDiscount($priceData, $discount, $productId)
    {
        $final = self::finalPriceWithDiscount($priceData, $discount);

        $price = new \App\Models\Price();
        $price->orginal = $priceData;
        $price->final = $final;
        $price->discount_percentage = $discount;
        $price->product_id = $productId;
        try {
            $price->save();
        } catch (Exception $exception) {
            return $exception;
        }
        return 'ok';

    }

    private function priceWithoutDiscount($priceData, $productId)
    {
        $price = new \App\Models\Price();
        $price->orginal = $priceData;
        $price->product_id = $productId;
        $price->final = $priceData;
        $price->discount_percentage = null;
        try {
            $price->save();
        } catch (Exception $exception) {
            return $exception;
        }
        return 'ok';
    }

    private function finalPriceWithDiscount($price, $discount)
    {
        $finalPrice = (int)$price - ((int)$price * ($discount / 100));
        return $finalPrice;
    }

}
