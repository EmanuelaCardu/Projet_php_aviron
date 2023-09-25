<?php

namespace App\Repository;

use App\Entity\CourEau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourEau>
 *
 * @method CourEau|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourEau|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourEau[]    findAll()
 * @method CourEau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourEauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourEau::class);
    }

//    /**
//     * @return CourEau[] Returns an array of CourEau objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CourEau
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
