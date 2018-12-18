<?php

namespace App\Repository;

use App\Entity\Reservation;
use Symfony\Component\Security\Core\Security;
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
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findBySectorsByDate(\DateTime $value): array
    {
        $reservations = $this->createQueryBuilder('r')
            ->andWhere('r.status = :active')
            ->andWhere('r.dateTo >= :val')
            ->setParameter('val', $value)
            ->setParameter('active', true)
            ->orderBy('r.dateFrom', 'ASC')
            ->getQuery()
            ->getResult();
        return $reservations;
    }

    public function isAvailableReservationRange(string $sector, \DateTime $dateFrom, \DateTime $dateTo): bool
    {
        return ($this->isAvailableDateFrom($sector, $dateFrom)) &&
            ($this->isAvailableDateTo($sector, $dateTo, $dateFrom));
    }

    public function isAvailableDateFrom(string $sector, \DateTime $dateFrom): bool
    {
        foreach ($this->findBusyFields($sector) as $range) {
            if (($range->getDateFrom() <= $dateFrom) && ($dateFrom < $range->getDateTo())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws
     */
    public function findBusyFields(string $sector): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.sectorName = :sector')
            ->andWhere('r.dateTo > :dateTo')
            ->andWhere('r.status = :active')
            ->setParameter('sector', $sector)
            ->setParameter('active', true)
            ->setParameter('dateTo', new \DateTime("today"))
            ->getQuery()
            ->getResult();
    }

    public function isAvailableDateTo(string $sector, \DateTime $dateTo, \DateTime $dateFrom): bool
    {
        return (($dateFrom < $dateTo) && ($dateTo <= $this->findAvailableDateTo($sector, $dateFrom)));
    }

    /**
     * @throws
     */
    public function findAvailableDateTo(string $sector, \DateTime $dateFrom): \DateTime
    {
        $data = $this->createQueryBuilder('r')
            ->andWhere('r.sectorName = :sector')
            ->andWhere('r.dateFrom > :dateFrom')
            ->andWhere('r.status = :active')
            ->setParameter('sector', $sector)
            ->setParameter('active', true)
            ->setParameter('dateFrom', $dateFrom)
            ->orderBy('r.dateFrom', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $date = new \DateTime('+30days');
        $maxDateTo = $date->setTime('08', '00');

        return $data ? $data->getDateFrom() : $maxDateTo;
    }

    public function findOneByIdField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByUser($userId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.userId = :val')
            ->setParameter('val', $userId)
            ->orderBy('r.dateFrom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
