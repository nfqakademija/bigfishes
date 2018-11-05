<?php

namespace App\Repository;

use App\Entity\MyStuff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MyStuff|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyStuff|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyStuff[]    findAll()
 * @method MyStuff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyStuffRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MyStuff::class);
    }

//    /**
//     * @return MyStuff[] Returns an array of MyStuff objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MyStuff
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
