<?php


namespace App\Services;


class Hours
{
    public function hoursTotal(\DateTime $dateFrom, \DateTime $dateTo)
    {
        try {
            $interval = $dateFrom->diff($dateTo);
            $days = (int) $interval->format('%d');
            $hours = (int) $interval->format('%h');
            $hoursTotal = $days * 24 + $hours;
        } catch (\Exception $e) {
            $hoursTotal = new \DateTime('now');
        }
        return  $hoursTotal;
    }
}
