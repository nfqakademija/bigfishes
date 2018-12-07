<?php

namespace App\Services;

use App\Entity\Reservation;

class PricesCalculation
{
    public function fishingPriceCalculation($fishersNumber, $hours)
    {
        if ($fishersNumber === 1) {
            return $fishersNumber * ($hours / 12 * Reservation::PRICE_FISHING_12_H);
        } elseif ($fishersNumber === 2) {
            return $fishersNumber * ($hours / 12 * Reservation::PRICE_FISHING_12_H * Reservation::DISCOUNT);
        }
        return false;
    }

    public function housePriceCalculation($hours)
    {
        return $hours / 12 * Reservation::PRICE_HOUSE_12_H;
    }

    public function totalPriceCalculation($priceFirst, $priceSecond)
    {
        return $priceFirst + $priceSecond;
    }
}
