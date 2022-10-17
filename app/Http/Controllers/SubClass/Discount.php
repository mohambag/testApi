<?php

namespace App\Http\Controllers\SubClass;

class Discount
{

    public function discountChecker($sku, $categories)
    {
        $discountSKU = self::skuCheck($sku);
        $discountCategory = self::categoryCheck($categories);
        $finalDiscount = $discountCategory + $discountSKU;
        return $finalDiscount;
    }

    private function skuCheck($sku)
    {
        if ($sku == '000003') {
            $discount=15;
            return $discount;
        }
        return 0;
    }

    private function categoryCheck($categories=[])
    {
        if(in_array(1,$categories)){//check insurance category
            $discount=30;
            return $discount;
        }
        return 0;
    }

}
