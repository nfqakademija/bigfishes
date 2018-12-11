<?php

namespace App\Service;

use App\Entity\Reservation;

class ReservationService
{
    public function hoursTotal(\DateTime $dateFrom, \DateTime $dateTo): int
    {
        $interval = $dateFrom->diff($dateTo);
        $days = (int)$interval->format('%d');
        $hours = (int)$interval->format('%h');
        $hoursTotal = $days * 24 + $hours;

        return $hoursTotal;
    }

    public function fishingPriceCalculation(int $fishersNumber, int $hours): int
    {
        if ($fishersNumber === 1) {
            return $fishersNumber * ($hours / 12 * Reservation::PRICE_FISHING_12_H);
        } elseif ($fishersNumber === 2) {
            return $fishersNumber * ($hours / 12 * Reservation::PRICE_FISHING_12_H * Reservation::DISCOUNT);
        }
        return false;
    }

    public function housePriceCalculation(int $hours): int
    {
        return $hours / 12 * Reservation::PRICE_HOUSE_12_H;
    }

    public function totalPriceCalculation(int $priceFirst, int $priceSecond): int
    {
        return $priceFirst + $priceSecond;
    }

    public function isTimeFrom08(\DateTime $date): bool
    {
        return ($this->isWeekendTime($date) && ($date->format('H') === '08'));
    }

    public function isWeekendTime(\DateTime $date): bool
    {
        return ($date->format('l') === 'Saturday' || $date->format('l') === 'Sunday');
    }
}
