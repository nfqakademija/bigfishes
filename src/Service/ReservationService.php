<?php

namespace App\Service;

use App\Entity\Reservation;

class ReservationService
{
    private $sectors = [
        "sector1" => 'Pirmas Sektorius',
        "sector2" => 'Antras Sektorius',
        "sector3" => 'Trečias Sektorius',
        "sector4" => 'Ketvirtas Sektorius',
        "sector5" => 'Penktas Sektorius',
        "sector6" => 'Šeštas Sektorius',
        "sector7" => 'Septintas Sektorius',
    ];
    private $sectorKey = 'name';
    private $dateKey = 'reservation_dates';

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

    public function createReservationDataArray($reservations)
    {
        $reservationsData = [];
        foreach ($this->sectors as $key => $sector) {
            $reservationsData [$key][$this->sectorKey] = $sector;
            foreach ($reservations as $name => $reservation) {
                if ($reservation->getSectorName() === $sector) {
                    $reservationsData [$key][$this->dateKey][$name]['dateFrom'] =
                        $reservation->getDateFrom()->format('Y-m-d');
                    $reservationsData [$key][$this->dateKey][$name]['timeFrom'] =
                        $reservation->getDateFrom()->format('H');
                    $reservationsData [$key][$this->dateKey][$name]['dateTo'] =
                        $reservation->getDateTo()->format('Y-m-d');
                    $reservationsData [$key][$this->dateKey][$name]['timeTo'] =
                        $reservation->getDateTo()->format('H');
                    $reservationsData [$key][$this->dateKey][$name]['name'] =
                        $reservation->getName();
                }
            }
        }
        return $reservationsData;
    }

    public function isSectorValid ($sector){
        foreach ($this->sectors as $realKey=>$realSector){
            if ($sector == $realKey){
                return true;
            }
        }
    }

    public function sectorKeyToName ($key){
        foreach ($this->sectors as $realKey=>$realSector){
            if ($realKey == $key){
                return $realSector;
            }
        }
    }

    public function sectorNameToKey ($name){
        foreach ($this->sectors as $realKey=>$realSector){
            if ($realSector == $name){
                return $realKey;
            }
        }
    }
}
