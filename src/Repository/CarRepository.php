<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Car::class);
    }


    public function searchCar($criteres)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cities', 'city')

            ->Where('city.name = :cityName')
            ->setParameter('cityName', $criteres['city']->getName())

            ->andWhere('c.color = :color')
            ->setParameter('color', $criteres['color'])

            ->andWhere('c.carburant = :carburant')
            ->setParameter('carburant', $criteres['carburant'])

            ->andWhere('c.price > :minimumPrice')
            ->setParameter('minimumPrice', $criteres['minimumPrice'])
            ->andWhere('c.price < :maximumPrice')
            ->setParameter('maximumPrice', $criteres['maximumPrice'])

            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Car
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */
}
