<?php

namespace App\Services;

class Prices
{
    const PRICE_FISHING_12_H = 10;
    const PRICE_HOUSE_12_H = 10;
    const DISCOUNT = 0.9;

    public function fishingPriceCalculation($fishersNumber, $hours)
    {
        if ($fishersNumber === 1) {
            return $fishersNumber * ($hours / 12 * self::PRICE_FISHING_12_H);
        } elseif ($fishersNumber === 2) {
            return $fishersNumber * ($hours / 12 * self::PRICE_FISHING_12_H * self::DISCOUNT);
        }
        return false;
    }

    public function housePriceCalculation($hours)
    {
        return $hours / 12 * self::PRICE_HOUSE_12_H;
    }

    public function totalPriceCalculation($priceFirst, $priceSecond)
    {
        return $priceFirst + $priceSecond;
    }
}
