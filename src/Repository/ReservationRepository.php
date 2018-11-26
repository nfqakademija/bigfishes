<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    private $sectors = [
        "Sector1" => 'Pirmas Sektorius',
        "Sector2" => 'Antras Sektorius',
        "Sector3" => 'Trečias Sektorius',
        "Sector4" => 'Ketvirtas Sektorius',
        "Sector5" => 'Penktas Sektorius',
        "Sector6" => 'Šeštas Sektorius',
        "Sector7" => 'Septintas Sektorius',
    ];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findBySectorsByDate(\DateTime $value): array
    {
        $reservations = [];
        foreach ($this->sectors as $key => $sector) {
            $reservations [$key]['name'] = $sector;
            $reservations [$key]['reservation_dates'] = $this->createQueryBuilder('r')
                ->select(
                    'r.dateFrom',
                    'r.dateFrom as timeFrom',
                    'r.dateTo',
                    'r.dateTo as timeTo',
                    'r.name'
                )
                ->andWhere('r.sectorName = :sector')
                ->andWhere('r.status = :active')
                ->andWhere('r.dateTo >= :val')
                ->setParameter('sector', $sector)
                ->setParameter('val', $value)
                ->setParameter('active', true)
                ->orderBy('r.id', 'ASC')
                ->getQuery()
                ->getResult()
                ;
        }

        foreach ($reservations as $sector => $sectors) {
            foreach ($sectors as $key => $dates) {
                if ($key === 'reservation_dates') {
                    foreach ($dates as $keys => $times) {
                        foreach ($times as $name => $data) {
                            $name == 'dateFrom'
                                ? $reservations[$sector][$key][$keys]['dateFrom'] = $data->format('Y-m-d')
                                : $data;
                            $name == 'timeFrom'
                                ? $reservations[$sector][$key][$keys]['timeFrom'] = $data->format('H')
                                : $data;
                            $name == 'dateTo'
                                ? $reservations[$sector][$key][$keys]['dateTo'] = $data->format('Y-m-d')
                                : $data;
                            $name == 'timeTo'
                                ? $reservations[$sector][$key][$keys]['timeTo'] = $data->format('H')
                                : $data;
                        }
                    }
                }
            }
        }
        return $reservations;
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
