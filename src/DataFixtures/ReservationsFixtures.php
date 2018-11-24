<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $set1 = $this->collectData(
            new \DateTime('2018-11-27'),
            '20',
            new \DateTime('2018-11-29'),
            '20',
            'Zigmas',
            2,
            'Pirmas Sektorius',
            80,
            5,
            48,
            'unpaid',
            1
        );

        $set2 = $this->collectData(
            new \DateTime('2018-11-30'),
            '20',
            new \DateTime('2018-12-01'),
            '20',
            'Paulius',
            1,
            'Pirmas Sektorius',
            20,
            9,
            24,
            'unpaid',
            1
        );

        $set3 = $this->collectData(
            new \DateTime('2018-12-07'),
            '08',
            new \DateTime('2018-12-09'),
            '20',
            'Juozas',
            2,
            'Pirmas Sektorius',
            120,
            7,
            60,
            'unpaid',
            1
        );


        $set4 = $this->collectData(
            new \DateTime('2018-12-04'),
            '20',
            new \DateTime('2018-12-05'),
            '08',
            'Povilas',
            1,
            'Antras Sektorius',
            10,
            12,
            12,
            'unpaid',
            1
        );

        $set5 = $this->collectData(
            new \DateTime('2018-12-14'),
            '20',
            new \DateTime('2018-12-15'),
            '20',
            'Stasys',
            1,
            'Antras Sektorius',
            20,
            20,
            24,
            'unpaid',
            1
        );

        $set6 = $this->collectData(
            new \DateTime('2018-12-20'),
            '20',
            new \DateTime('2018-12-21'),
            '20',
            'Albinas',
            2,
            'Antras Sektorius',
            40,
            17,
            24,
            'unpaid',
            1
        );


        $set7 = $this->collectData(
            new \DateTime('2018-11-30'),
            '20',
            new \DateTime('2018-12-02'),
            '20',
            'Virgis',
            2,
            'Trečias Sektorius',
            80,
            11,
            48,
            'unpaid',
            1
        );

        $set8 = $this->collectData(
            new \DateTime('2018-12-05'),
            '08',
            new \DateTime('2018-12-05'),
            '20',
            'Petras',
            1,
            'Trečias Sektorius',
            10,
            13,
            12,
            'unpaid',
            1
        );

        $set9 = $this->collectData(
            new \DateTime('2018-12-11'),
            '08',
            new \DateTime('2018-12-12'),
            '20',
            'Paulius',
            1,
            'Trečias Sektorius',
            30,
            9,
            36,
            'unpaid',
            1
        );

        $set10 = $this->collectData(
            new \DateTime('2018-12-16'),
            '20',
            new \DateTime('2018-12-17'),
            '08',
            'Zigmas',
            2,
            'Trečias Sektorius',
            10,
            5,
            12,
            'unpaid',
            1
        );

        $set11 = $this->collectData(
            new \DateTime('2018-12-20'),
            '20',
            new \DateTime('2018-12-23'),
            '20',
            'Sigis',
            2,
            'Trečias Sektorius',
            120,
            5,
            72,
            'unpaid',
            1
        );


        $set12 = $this->collectData(
            new \DateTime('2018-12-04'),
            '20',
            new \DateTime('2018-12-05'),
            '20',
            'Saulius',
            1,
            'Ketvirtas Sektorius',
            20,
            15,
            24,
            'unpaid',
            1
        );


        $set13 = $this->collectData(
            new \DateTime('2018-12-12'),
            '20',
            new \DateTime('2018-12-16'),
            '20',
            'Linas',
            2,
            'Penktas Sektorius',
            160,
            12,
            96,
            'unpaid',
            1
        );


        $set14 = $this->collectData(
            new \DateTime('2018-12-06'),
            '20',
            new \DateTime('2018-12-09'),
            '08',
            'Rokas',
            1,
            'Šeštas Sektorius',
            50,
            8,
            60,
            'unpaid',
            1
        );

        $set15 = $this->collectData(
            new \DateTime('2018-12-12'),
            '20',
            new \DateTime('2018-12-15'),
            '20',
            'Algis',
            2,
            'Šeštas Sektorius',
            120,
            9,
            72,
            'unpaid',
            1
        );


        $set16 = $this->collectData(
            new \DateTime('2018-11-29'),
            '20',
            new \DateTime('2018-12-02'),
            '20',
            'Jonas',
            1,
            'Septintas Sektorius',
            60,
            5,
            72,
            'unpaid',
            1
        );

        $set17 = $this->collectData(
            new \DateTime('2018-12-08'),
            '08',
            new \DateTime('2018-12-10'),
            '20',
            'Jonas',
            1,
            'Septintas Sektorius',
            50,
            5,
            60,
            'unpaid',
            1
        );


        $manager->persist($set1);
        $manager->persist($set2);
        $manager->persist($set3);
        $manager->persist($set4);
        $manager->persist($set5);
        $manager->persist($set6);
        $manager->persist($set7);
        $manager->persist($set8);
        $manager->persist($set9);
        $manager->persist($set10);
        $manager->persist($set11);
        $manager->persist($set12);
        $manager->persist($set13);
        $manager->persist($set14);
        $manager->persist($set15);
        $manager->persist($set16);
        $manager->persist($set17);

        $manager->flush();
    }

    public function collectData(
        \DateTime $dateFrom,
        $timeFrom,
        \DateTime $dateTo,
        $timeTo,
        $name,
        $fishersNumber,
        $sectorName,
        $amount,
        $userId,
        $hours,
        $paymentStatus,
        $status
    ) {
        $reservation = new Reservation();
        $reservation->setDateFrom($dateFrom->setTime($timeFrom, '00'));
        $reservation->setDateTo($dateTo->setTime($timeTo, '00'));
        $reservation->setName($name);
        $reservation->setFishersNumber($fishersNumber);
        $reservation->setSectorName($sectorName);

        if ($sectorName === "Trečias Sektorius") {
            $reservation->setHouse('1');
        } else {
            $reservation->setHouse('0');
        }

        $reservation->setHours($hours);
        $reservation->setAmount($amount);
        $reservation->setUserId($userId);
        $reservation->setPaymentStatus($paymentStatus);
        $reservation->setStatus($status);

        return $reservation;
    }
}
